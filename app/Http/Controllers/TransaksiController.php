<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\MetodePembayaran;
use App\Models\StokDistribusi;
use App\Models\StatusTransaksi;
use App\Models\Transaksi;
use App\Models\Keuangan;
use App\Models\Pengepul;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->initializeMidtrans();
    }

    private function initializeMidtrans()
    {
        try {
            Config::$serverKey = config('midtrans.serverKey');
            Config::$clientKey = config('midtrans.clientKey');
            Config::$isProduction = config('midtrans.isProduction', false);
            Config::$isSanitized = config('midtrans.isSanitized', true);
            Config::$is3ds = config('midtrans.is3ds', true);

            if (empty(Config::$serverKey)) {
                throw new \Exception('Midtrans server key is empty');
            }

            if (empty(Config::$clientKey)) {
                throw new \Exception('Midtrans client key is empty');
            }

            Log::info('Midtrans configuration verified', [
                'environment' => Config::$isProduction ? 'Production' : 'Sandbox',
                'serverKeyPrefix' => substr(Config::$serverKey, 0, 6) . '...',
                'clientKeyPrefix' => substr(Config::$clientKey, 0, 6) . '...',
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans initialization failed: ' . $e->getMessage());
            throw $e;
        }
    }

public function index()
{
    $transaksis = Transaksi::with(['pengepul', 'detailTransaksi.stokDistribusi', 'statusTransaksi', 'metodePembayaran'])
        ->orderBy('tgl_transaksi', 'desc')
        ->paginate(10);

    $transaksis->getCollection()->transform(function ($transaksi) {
        $detail = $transaksi->detailTransaksi->first();
        return [
            'id' => $transaksi->id,
            'tgl_transaksi' => $transaksi->tgl_transaksi->format('d-m-Y H:i:s'),
            'username' => $transaksi->pengepul->nama,
            'nama_stok' => $detail ? $detail->stokDistribusi->nama_stok : 'N/A',
            'kuantitas' => $detail ? $detail->kuantitas : 0,
            'total_transaksi' => $detail ? $detail->sub_total : 0,
            'metode_pembayaran' => $transaksi->metodePembayaran->nama_metode ?? 'N/A',
            'status' => $transaksi->statusTransaksi ? $transaksi->statusTransaksi->nama_status : 'Menunggu Pembayaran',
            'snap_token' => $transaksi->snap_token,
            'payment_status' => $transaksi->payment_status,
        ];
    });
    return view('owner.transaksi.index', compact('transaksis'));
}

    public function show($id)
    {
        $transaksi = Transaksi::with(['pengepul', 'detailTransaksi.stokDistribusi', 'metodePembayaran', 'statusTransaksi'])
            ->findOrFail($id);

        $detail = $transaksi->detailTransaksi->first();

        $transaksiDetail = [
            'id' => $transaksi->id,
            'username' => $transaksi->pengepul->nama,
            'nama_stok' => $detail ? $detail->stokDistribusi->nama_stok : 'N/A',
            'kuantitas' => $detail ? $detail->kuantitas : 0,
            'total_transaksi' => $detail ? $detail->sub_total : 0,
            'tanggal_transaksi' => $transaksi->tgl_transaksi->format('d-m-Y H:i:s'),
            'metode_pembayaran' => $transaksi->metodePembayaran->nama_metode ?? 'N/A',
            'status' => $transaksi->statusTransaksi ? $transaksi->statusTransaksi->nama_status : 'Menunggu Pembayaran',
            'snap_token' => $transaksi->snap_token,
            'payment_status' => $transaksi->payment_status,
            'order_id' => $transaksi->order_id,
        ];

        $statusOptions = [
            'Pembayaran Lunas',
            'Dikemas',
            'Dikirim',
            'Selesai'
        ];

        if (request()->ajax()) {
            return response()->json([
                'id' => $transaksiDetail['id'],
                'username' => $transaksiDetail['username'],
                'nama_stok' => $transaksiDetail['nama_stok'],
                'kuantitas' => $transaksiDetail['kuantitas'],
                'total_transaksi' => $transaksiDetail['total_transaksi'],
                'metode_pembayaran' => $transaksiDetail['metode_pembayaran'],
                'tanggal_transaksi' => $transaksiDetail['tanggal_transaksi'],
                'status' => $transaksiDetail['status'],
                'snap_token' => $transaksiDetail['snap_token'],
                'payment_status' => $transaksiDetail['payment_status'],
                'statusOptions' => $statusOptions
            ]);
        }

        return view('owner.transaksi.show', compact('transaksiDetail', 'statusOptions'));
    }

    public function updateStatus(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:Pembayaran Lunas,Dikemas,Dikirim,Selesai',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak valid'
                ], 422);
            }
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $statusMap = [
            'Pembayaran Lunas' => 1,
            'Dikemas' => 2,
            'Dikirim' => 3,
            'Selesai' => 4,
        ];

        $statusId = $statusMap[$request->status];

        DB::table('transaksis')->where('id', $id)->update([
            'id_status_transaksi' => $statusId
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah'
            ]);
        }

        return redirect()->route('owner.transaksi.show', $id)->with('success', 'Status berhasil diubah');
    }

    public function indexPengepul()
    {
        $pengepul = Auth::user()->pengepul;

        $transaksis = Transaksi::with(['detailTransaksi.stokDistribusi', 'statusTransaksi'])
            ->where('id_pengepul', $pengepul->id)
            ->paginate(10);

        $transaksis->getCollection()->transform(function ($transaksi) use ($pengepul) {
            $latestStatus = $transaksi->statusTransaksi()
                ->orderBy('id', 'desc')
                ->first();

            $detail = $transaksi->detailTransaksi->first();

            return [
                'id' => $transaksi->id,
                'username' => $pengepul->nama,
                'nama_stok' => $detail ? $detail->stokDistribusi->nama_stok : 'N/A',
                'kuantitas' => $detail ? $detail->kuantitas : 0,
                'total_transaksi' => $detail ? $detail->sub_total : 0,
                'metode_pembayaran' => $transaksi->metodePembayaran->nama_metode ?? 'N/A',
                'status' => $latestStatus ? $latestStatus->nama_status : 'Menunggu Pembayaran',
                'snap_token' => $transaksi->snap_token,
            ];
        });

        return view('pengepul.transaksi.index', compact('transaksis'));
    }

    public function showPengepul($id)
    {
        $pengepul = Auth::user()->pengepul;

        $transaksi = Transaksi::with(['detailTransaksi.stokDistribusi', 'metodePembayaran', 'statusTransaksi'])
            ->where('id_pengepul', $pengepul->id)
            ->findOrFail($id);

        $latestStatus = $transaksi->statusTransaksi()
            ->orderBy('id', 'desc')
            ->first();

        $detail = $transaksi->detailTransaksi->first();

        $transaksiDetail = [
            'id' => $transaksi->id,
            'username' => $pengepul->nama,
            'nama_stok' => $detail ? $detail->stokDistribusi->nama_stok : 'N/A',
            'kuantitas' => $detail ? $detail->kuantitas : 0,
            'total_transaksi' => $detail ? $detail->sub_total : 0,
            'metode_pembayaran' => $transaksi->metodePembayaran->nama_metode,
            'tanggal_transaksi' => $transaksi->tgl_transaksi->format('d-m-Y H:i:s'),
            'metode_pembayaran' => $transaksi->metodePembayaran->nama_metode ?? 'N/A',
            'status' => $latestStatus ? $latestStatus->nama_status : 'Menunggu Pembayaran',
            'snap_token' => $transaksi->snap_token,
        ];

        if (request()->ajax()) {
            $statusOptions = [
                'Pembayaran Lunas',
                'Dikemas',
                'Dikirim',
                'Selesai'
            ];

            return response()->json([
                'id' => $transaksiDetail['id'],
                'username' => $transaksiDetail['username'],
                'nama_stok' => $transaksiDetail['nama_stok'],
                'kuantitas' => $transaksiDetail['kuantitas'],
                'total_transaksi' => $transaksiDetail['total_transaksi'],
                'metode_pembayaran' => $transaksiDetail['metode_pembayaran'],
                'tanggal_transaksi' => $transaksiDetail['tanggal_transaksi'],
                'status' => $transaksiDetail['status'],
                'snap_token' => $transaksiDetail['snap_token'],
                'statusOptions' => $statusOptions
            ]);
        }

        return view('pengepul.transaksi.show', compact('transaksiDetail'));
    }

    public function create($stokId)
    {
        $stokDistribusi = StokDistribusi::findOrFail($stokId);
        $metodePembayaran = MetodePembayaran::all();

        return view('pengepul.transaksi.create', compact('stokDistribusi', 'metodePembayaran'));
    }

    private function validateMidtransConfiguration()
    {
        $serverKey = config('midtrans.serverKey');
        $clientKey = config('midtrans.clientKey');

        if (empty($serverKey) || empty($clientKey)) {
            throw new \Exception('Midtrans configuration is incomplete. Server key or client key is missing.');
        }

        if (strlen($serverKey) < 10) {
            throw new \Exception('Server key appears to be invalid (too short).');
        }

        if (strlen($clientKey) < 10) {
            throw new \Exception('Client key appears to be invalid (too short).');
        }

        return true;
    }

    private function isDigitalPayment($paymentMethodName)
    {
        $digitalPaymentKeywords = ['transfer', 'digital', 'pembayaran digital', 'online', 'e-wallet', 'credit card', 'debit'];
        $paymentMethodLower = strtolower($paymentMethodName);

        foreach ($digitalPaymentKeywords as $keyword) {
            if (strpos($paymentMethodLower, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    private function isCashPayment($paymentMethodName)
    {
        $cashPaymentKeywords = ['tunai', 'cash', 'pembayaran tunai', 'cod', 'bayar ditempat'];
        $paymentMethodLower = strtolower($paymentMethodName);

        foreach ($cashPaymentKeywords as $keyword) {
            if (strpos($paymentMethodLower, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    public function store(Request $request, $stokId)
    {
        $validator = Validator::make($request->all(), [
            'kuantitas' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|exists:metode_pembayarans,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Kuantitas harus berisi angka dan metode pembayaran harus dipilih')
                ->withInput();
        }

        $stokDistribusi = StokDistribusi::findOrFail($stokId);

        if ($stokDistribusi->jumlah_stok < $request->kuantitas) {
            return redirect()->back()
                ->with('error', 'Stok tidak mencukupi')
                ->withInput();
        }

        $subTotal = $stokDistribusi->harga_stok * $request->kuantitas;

        DB::beginTransaction();

        try {
            $metodePembayaran = MetodePembayaran::findOrFail($request->metode_pembayaran);
            $user = Auth::user();

            $transaksi = Transaksi::create([
                'tgl_transaksi' => now(),
                'id_pengepul' => $user->pengepul->id,
                'id_metode_pembayaran' => $metodePembayaran->id,
                'id_status_transaksi' => 1,
                'payment_status' => 'pending',
                'snap_token' => null,
                'order_id' => null,
            ]);

            DetailTransaksi::create([
                'kuantitas' => $request->kuantitas,
                'sub_total' => $subTotal,
                'id_transaksi' => $transaksi->id,
                'id_stok_distribusi' => $stokDistribusi->id,
            ]);

            $initialStatus = 'Menunggu Pembayaran';
            $initialStatusId = 1;
            $paymentStatus = 'pending';

            if ($this->isCashPayment($metodePembayaran->nama_metode)) {
                $initialStatus = 'Pembayaran Lunas';
                $initialStatusId = 2;
                $paymentStatus = 'success';
            }

            $transaksi->update([
                'id_status_transaksi' => $initialStatusId,
                'payment_status' => $paymentStatus
            ]);

            $stokDistribusi->decrement('jumlah_stok', $request->kuantitas);

            $tanggalRekapitulasi = now()->toDateString();

            $keuangan = Keuangan::where('tgl_rekapitulasi', $tanggalRekapitulasi)->first();

            if ($keuangan) {
                $keuangan->update([
                    'saldo_pemasukkan' => $keuangan->saldo_pemasukkan + $subTotal,
                    'total_penjualan' => $keuangan->total_penjualan + $request->kuantitas,
                ]);
            } else {
                $keuangan = Keuangan::create([
                    'tgl_rekapitulasi' => $tanggalRekapitulasi,
                    'saldo_pengeluaran' => 0,
                    'saldo_pemasukkan' => $subTotal,
                    'total_penjualan' => $request->kuantitas,
                    'id_transaksi' => $transaksi->id,
                ]);
            }

            $transaksi->update(['id_keuangan' => $keuangan->id]);

            if ($this->isDigitalPayment($metodePembayaran->nama_metode)) {
                try {
                    $this->validateMidtransConfiguration();
                    $this->initializeMidtrans();

                    $orderId = 'ORDER-' . $transaksi->id . '-' . time();

                    $params = [
                        'transaction_details' => [
                            'order_id' => $orderId,
                            'gross_amount' => $subTotal,
                        ],
                        'item_details' => [
                            [
                                'id' => (string) $stokDistribusi->id,
                                'price' => (int) $stokDistribusi->harga_stok,
                                'quantity' => (int) $request->kuantitas,
                                'name' => $stokDistribusi->nama_stok,
                            ]
                        ],
                        'customer_details' => [
                            'first_name' => $user->pengepul->nama,
                            'email' => $user->email,
                            'phone' => $user->pengepul->no_telp ?? '08123456789',
                        ],
                        'callbacks' => [
                            'finish' => route('payment.return'),
                            'error' => route('pengepul.transaksi.show', $transaksi->id),
                            'pending' => route('pengepul.transaksi.show', $transaksi->id),
                        ],
                        'notification_url' => route('payment.callback'),
                    ];

                    $snapToken = Snap::getSnapToken($params);

                    $transaksi->update([
                        'snap_token' => $snapToken,
                        'order_id' => $orderId,
                        'payment_status' => 'pending'
                    ]);

                } catch (\Exception $e) {
                    DB::rollBack();

                    $errorMessage = $this->getMidtransErrorMessage($e);

                    return redirect()->back()
                        ->with('error', $errorMessage)
                        ->withInput();
                }
            }

            DB::commit();

            if ($this->isDigitalPayment($metodePembayaran->nama_metode)) {
                return redirect()->route('pengepul.transaksi.payment', $transaksi->id)
                    ->with('success', 'Transaksi berhasil dibuat. Silakan lanjutkan pembayaran.');
            }

            return redirect()->route('pengepul.transaksi.show', $transaksi->id)
                ->with('success', 'Transaksi berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Transaction error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.')
                ->withInput();
        }
    }

    private function getMidtransErrorMessage(\Exception $e)
    {
        if (strpos($e->getMessage(), 'unauthorized') !== false ||
            strpos($e->getMessage(), '401') !== false ||
            strpos($e->getMessage(), 'Unauthorized') !== false) {
            return 'Pembayaran digital sedang tidak tersedia. Konfigurasi server key tidak valid.';
        }

        if (strpos($e->getMessage(), 'configuration') !== false) {
            return 'Pembayaran digital sedang tidak tersedia. Konfigurasi pembayaran belum lengkap.';
        }

        if (strpos($e->getMessage(), 'network') !== false ||
            strpos($e->getMessage(), 'connection') !== false) {
            return 'Pembayaran digital sedang tidak tersedia karena masalah koneksi.';
        }

        return 'Gagal memproses pembayaran digital. Silakan coba metode pembayaran lain.';
    }

    public function payment($id)
    {
        $pengepul = Auth::user()->pengepul;

        $transaksi = Transaksi::with(['detailTransaksi.stokDistribusi', 'metodePembayaran'])
            ->where('id_pengepul', $pengepul->id)
            ->findOrFail($id);

        $metodePembayaran = $transaksi->metodePembayaran;
        if (!$this->isDigitalPayment($metodePembayaran->nama_metode)) {
            return redirect()->route('pengepul.transaksi.show', $id)
                ->with('error', 'Transaksi ini tidak memerlukan pembayaran digital');
        }

        if (!$transaksi->snap_token) {
            return redirect()->route('pengepul.transaksi.show', $id)
                ->with('error', 'Token pembayaran tidak ditemukan. Transaksi mungkin sudah selesai atau terjadi kesalahan.');
        }

        $detail = $transaksi->detailTransaksi->first();

        $paymentData = [
            'transaksi' => $transaksi,
            'detail' => $detail,
            'snap_token' => $transaksi->snap_token,
            'total_amount' => $detail->sub_total,
            'stok_name' => $detail->stokDistribusi->nama_stok,
            'quantity' => $detail->kuantitas,
        ];

        // REMOVED: Premature payment status update
        // Don't update status here - let Midtrans callback handle it

        return view('pengepul.transaksi.payment', compact('paymentData'));
    }

    public function updatePaymentStatus($transaksiId, $paymentStatus = 'success', $statusTransaksiId = null)
    {
        try {
            // Find the transaction
            $transaksi = Transaksi::findOrFail($transaksiId);

            // Validate payment status
            $validPaymentStatuses = ['pending', 'success', 'failed', 'cancelled'];
            if (!in_array($paymentStatus, $validPaymentStatuses)) {
                throw new \InvalidArgumentException('Invalid payment status');
            }

            // Set default status_transaksi based on payment status
            if ($statusTransaksiId === null) {
                switch ($paymentStatus) {
                    case 'success':
                        $statusTransaksiId = 2; // Pembayaran Lunas
                        break;
                    case 'pending':
                        $statusTransaksiId = 1; // Menunggu Pembayaran
                        break;
                    case 'failed':
                    case 'cancelled':
                        $statusTransaksiId = 1; // Back to Menunggu Pembayaran
                        break;
                    default:
                        $statusTransaksiId = 1;
                }
            }

            // Update the transaction
            $transaksi->update([
                'payment_status' => $paymentStatus,
                'id_status_transaksi' => $statusTransaksiId,
            ]);

            Log::info('Payment status updated successfully', [
                'transaksi_id' => $transaksiId,
                'payment_status' => $paymentStatus,
                'status_transaksi_id' => $statusTransaksiId
            ]);

            return [
                'success' => true,
                'message' => 'Payment status updated successfully',
                'transaksi' => $transaksi->fresh()
            ];

        } catch (\Exception $e) {
            Log::error('Failed to update payment status', [
                'transaksi_id' => $transaksiId,
                'payment_status' => $paymentStatus,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to update payment status: ' . $e->getMessage(),
                'transaksi' => null
            ];
        }
    }

    public function markPaymentAsSuccess($transaksiId)
    {
        return $this->updatePaymentStatus($transaksiId, 'success', 2);
    }

    /**
     * Mark payment as failed (convenience method)
     *
     * @param int $transaksiId
     * @return array
     */
    public function markPaymentAsFailed($transaksiId)
    {
        return $this->updatePaymentStatus($transaksiId, 'failed', 1);
    }

    public function handlePaymentCallback(Request $request)
    {
        try {
            // Initialize Midtrans
            $this->initializeMidtrans();

            // Get notification from Midtrans
            $notification = new Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;

            Log::info('Payment callback received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            // Find transaction by order_id
            $transaksi = Transaksi::where('order_id', $orderId)->first();

            if (!$transaksi) {
                Log::error('Transaction not found for order_id: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
            }

            // Determine payment status based on Midtrans response
            $paymentStatus = 'pending';
            $statusTransaksiId = 1;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $paymentStatus = 'pending';
                    $statusTransaksiId = 1;
                } else if ($fraudStatus == 'accept') {
                    $paymentStatus = 'success';
                    $statusTransaksiId = 2;
                }
            } else if ($transactionStatus == 'settlement') {
                $paymentStatus = 'success';
                $statusTransaksiId = 2;
            } else if ($transactionStatus == 'pending') {
                $paymentStatus = 'pending';
                $statusTransaksiId = 1;
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $paymentStatus = 'failed';
                $statusTransaksiId = 1;
            }

            // Update payment status
            $result = $this->updatePaymentStatus($transaksi->id, $paymentStatus, $statusTransaksiId);

            if ($result['success']) {
                return response()->json(['status' => 'success', 'message' => 'Payment status updated']);
            } else {
                return response()->json(['status' => 'error', 'message' => $result['message']], 500);
            }

        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Callback processing failed'], 500);
        }
    }

    public function updatePaymentStatusAjax(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'payment_status' => 'required|in:success,pending,failed,cancelled',
                'payment_result' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payment status'
                ], 422);
            }

            $result = $this->updatePaymentStatus($id, $request->payment_status);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Payment status update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status'
            ], 500);
        }
    }

    public function checkPaymentStatus($id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);

            // Check if this is the authenticated user's transaction
            $pengepul = Auth::user()->pengepul;
            if ($transaksi->id_pengepul !== $pengepul->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'payment_status' => $transaksi->payment_status,
                'transaction_status' => $transaksi->statusTransaksi->nama_status ?? 'Unknown',
                'order_id' => $transaksi->order_id
            ]);

        } catch (\Exception $e) {
            Log::error('Payment status check error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to check payment status'
            ], 500);
        }
    }
}

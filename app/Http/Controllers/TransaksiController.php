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
            throw $e; // Re-throw to prevent silent failures
        }
    }

    public function index()
    {
        $transaksis = Transaksi::with(['pengepul', 'detailTransaksi.stokDistribusi', 'statusTransaksi', 'metodePembayaran'])
            ->orderBy('tgl_transaksi', 'desc')
            ->get()
            ->map(function ($transaksi) {
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
            'Pembayaran Valid',
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
            'status' => 'required|string|in:Pembayaran Valid,Dikemas,Dikirim,Selesai',
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
            'Pembayaran Valid' => 1,
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
            ->get()
            ->map(function ($transaksi) use ($pengepul) {
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
                'Pembayaran Valid',
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

        // Basic validation for key format
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
        // Validate input
        $validator = Validator::make($request->all(), [
            'kuantitas' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|exists:metode_pembayarans,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Kuantitas harus berisi angka dan metode pembayaran harus dipilih')
                ->withInput();
        }

        // Get stock data
        $stokDistribusi = StokDistribusi::findOrFail($stokId);

        // Check stock availability
        if ($stokDistribusi->jumlah_stok < $request->kuantitas) {
            return redirect()->back()
                ->with('error', 'Stok tidak mencukupi')
                ->withInput();
        }

        // Calculate subtotal
        $subTotal = $stokDistribusi->harga_stok * $request->kuantitas;

        DB::beginTransaction();

        try {
            $metodePembayaran = MetodePembayaran::findOrFail($request->metode_pembayaran);
            $user = Auth::user();

            // Create transaction
            $transaksi = Transaksi::create([
                'tgl_transaksi' => now(),
                'id_pengepul' => $user->pengepul->id,
                'id_metode_pembayaran' => $metodePembayaran->id,
                'id_status_transaksi' => 1, // Default: Menunggu Pembayaran
                'payment_status' => 'pending',
                'snap_token' => null,
                'order_id' => null,
            ]);

            // Create transaction detail
            DetailTransaksi::create([
                'kuantitas' => $request->kuantitas,
                'sub_total' => $subTotal,
                'id_transaksi' => $transaksi->id,
                'id_stok_distribusi' => $stokDistribusi->id,
            ]);

            // Handle payment method type
            $initialStatus = 'Menunggu Pembayaran';
            $initialStatusId = 1;
            $paymentStatus = 'pending';

            if ($this->isCashPayment($metodePembayaran->nama_metode)) {
                $initialStatus = 'Pembayaran Valid';
                $initialStatusId = 2;
                $paymentStatus = 'success';
            }

            // Update transaction status
            $transaksi->update([
                'id_status_transaksi' => $initialStatusId,
                'payment_status' => $paymentStatus
            ]);

            // Record status change


            // Update stock
            $stokDistribusi->decrement('jumlah_stok', $request->kuantitas);

            // Create financial record
            $keuangan = Keuangan::create([
                'tgl_rekapitulasi' => now()->toDateString(),
                'saldo_pengeluaran' => 0,
                'saldo_pemasukkan' => $subTotal,
                'total_penjualan' => $request->kuantitas,
                'id_transaksi' => $transaksi->id,
            ]);

            $transaksi->update(['id_keuangan' => $keuangan->id]);

            // Handle digital payment
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
                            'finish' => route('pengepul.transaksi.show', $transaksi->id),
                            'error' => route('pengepul.transaksi.show', $transaksi->id),
                            'pending' => route('pengepul.transaksi.show', $transaksi->id),
                        ]
                    ];

                    $snapToken = Snap::getSnapToken($params);

                    $transaksi->update([
                        'snap_token' => $snapToken,
                        'order_id' => $orderId,
                        'payment_status' => 'pending' // Reset to pending for digital payment
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

            // Redirect based on payment method
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

    // Helper method for Midtrans error messages
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

        return view('pengepul.transaksi.payment', compact('paymentData'));
    }

    public function handleCallback(Request $request)
    {
        try {
            $notification = new Notification();

            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status ?? null;

            Log::info('Midtrans callback received', [
                'order_id' => $orderId,
                'transaction_status' => $transaction,
                'payment_type' => $type,
                'fraud_status' => $fraud
            ]);

            // Extract transaction ID from order ID
            $orderParts = explode('-', $orderId);
            if (count($orderParts) < 2) {
                Log::error('Invalid order ID format: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Invalid order ID'], 400);
            }

            $transactionId = $orderParts[1];
            $transaksi = Transaksi::findOrFail($transactionId);

            // Update transaction status based on Midtrans response
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $this->updateTransactionStatus($transaksi, 'pending', 1);
                    } else {
                        $this->updateTransactionStatus($transaksi, 'success', 2);
                    }
                }
            } elseif ($transaction == 'settlement') {
                $this->updateTransactionStatus($transaksi, 'success', 2);
            } elseif ($transaction == 'pending') {
                $this->updateTransactionStatus($transaksi, 'pending', 1);
            } elseif ($transaction == 'deny') {
                $this->updateTransactionStatus($transaksi, 'failed', 1);
            } elseif ($transaction == 'expire') {
                $this->updateTransactionStatus($transaksi, 'expired', 1);
            } elseif ($transaction == 'cancel') {
                $this->updateTransactionStatus($transaksi, 'cancelled', 1);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function updateTransactionStatus($transaksi, $paymentStatus, $statusTransaksiId)
    {
        $transaksi->update([
            'payment_status' => $paymentStatus,
            'id_status_transaksi' => $statusTransaksiId
        ]);

        Log::info('Transaction status updated', [
            'transaction_id' => $transaksi->id,
            'payment_status' => $paymentStatus,
            'status_transaksi_id' => $statusTransaksiId
        ]);
    }

    public function checkPaymentStatus($id)
    {
        try {
            $transaksi = Transaksi::with('statusTransaksi')->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'payment_status' => $transaksi->payment_status,
                'transaction_status' => $transaksi->statusTransaksi->nama_status ?? 'Unknown'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        }
    }
}

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

        $statusOptions = StatusTransaksi::all()->pluck('nama_status')->toArray();

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
        try {
            $transaksi = Transaksi::findOrFail($id);

            $availableStatuses = StatusTransaksi::pluck('nama_status', 'id')->toArray();
            $statusNames = array_values($availableStatuses);

            $validator = Validator::make($request->all(), [
                'status' => 'required|string|in:' . implode(',', $statusNames),
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Status tidak valid. Status yang tersedia: ' . implode(', ', $statusNames)
                    ], 422);
                }
                return redirect()->back()->with('error', 'Status tidak valid');
            }

            $statusId = null;
            foreach ($availableStatuses as $id => $name) {
                if ($name === $request->status) {
                    $statusId = $id;
                    break;
                }
            }

            if (!$statusId) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Status tidak ditemukan'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Status tidak ditemukan');
            }

            $transaksi->update([
                'id_status_transaksi' => $statusId
            ]);

            Log::info('Transaction status updated', [
                'transaction_id' => $id,
                'old_status' => $transaksi->statusTransaksi->nama_status ?? 'Unknown',
                'new_status' => $request->status,
                'updated_by' => Auth::user()->id
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status berhasil diubah',
                    'new_status' => $request->status
                ]);
            }

            return redirect()->route('owner.transaksi.show', $id)->with('success', 'Status berhasil diubah');

        } catch (\Exception $e) {
            Log::error('Error updating transaction status: ' . $e->getMessage(), [
                'transaction_id' => $id,
                'requested_status' => $request->status ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengubah status: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah status');
        }
    }

    public function indexPengepul()
    {
        $pengepul = Auth::user()->pengepul;

        $transaksis = Transaksi::with(['detailTransaksi.stokDistribusi', 'statusTransaksi'])
            ->where('id_pengepul', $pengepul->id)
            ->orderBy('tgl_transaksi', 'desc')
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
                'tanggal_transaksi' => $transaksi->tgl_transaksi->format('d-m-Y H:i:s'),
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

        $latestStatus = $transaksi->statusTransaksi;

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
                'Pembayaran Lunas'
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
                $initialStatus = 'Menunggu';
                $initialStatusId = 1;
                $paymentStatus = 'success';
            }

            $transaksi->update([
                'id_status_transaksi' => $initialStatusId,
                'payment_status' => $paymentStatus
            ]);

            $stokDistribusi->decrement('jumlah_stok', $request->kuantitas);

            if ($this->isCashPayment($metodePembayaran->nama_metode)) {
                $this->updateOrCreateKeuangan($transaksi, $subTotal, $request->kuantitas);

                Log::info('Keuangan updated for cash payment', [
                    'transaksi_id' => $transaksi->id,
                    'sub_total' => $subTotal,
                    'kuantitas' => $request->kuantitas,
                    'metode_pembayaran' => $metodePembayaran->nama_metode
                ]);
            }

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

            return redirect()->route('pengepul.transaksi.index')
                ->with('success', 'Transaksi berhasil dibuat dan sedang menunggu konfirmasi.');

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

    private function updateOrCreateKeuangan($transaksi, $subTotal, $kuantitas)
    {
        try {
            if (!$transaksi || !is_numeric($subTotal) || !is_numeric($kuantitas)) {
                throw new \Exception('Invalid input data: transaksi, subTotal, or kuantitas is invalid');
            }

            $subTotal = (float) $subTotal;
            $kuantitas = (int) $kuantitas;

            $tanggalRekapitulasi = now()->toDateString();

            Log::info('Starting updateOrCreateKeuangan', [
                'transaksi_id' => $transaksi->id,
                'sub_total' => $subTotal,
                'kuantitas' => $kuantitas,
                'tanggal_rekapitulasi' => $tanggalRekapitulasi
            ]);

            $keuangan = Keuangan::where('tgl_rekapitulasi', $tanggalRekapitulasi)->first();

            if ($keuangan) {
                Log::info('Updating existing Keuangan record', [
                    'keuangan_id' => $keuangan->id,
                    'current_saldo_pemasukkan' => $keuangan->saldo_pemasukkan,
                    'current_total_penjualan' => $keuangan->total_penjualan,
                    'adding_sub_total' => $subTotal,
                    'adding_kuantitas' => $kuantitas
                ]);

                $newSaldoPemasukkan = $keuangan->saldo_pemasukkan + $subTotal;
                $newTotalPenjualan = $keuangan->total_penjualan + $kuantitas;

                $updated = $keuangan->update([
                    'saldo_pemasukkan' => $newSaldoPemasukkan,
                    'total_penjualan' => $newTotalPenjualan,
                ]);

                if (!$updated) {
                    throw new \Exception('Failed to update existing Keuangan record');
                }

                Log::info('Successfully updated existing Keuangan', [
                    'keuangan_id' => $keuangan->id,
                    'new_saldo_pemasukkan' => $newSaldoPemasukkan,
                    'new_total_penjualan' => $newTotalPenjualan
                ]);

            } else {
                Log::info('Creating new Keuangan record', [
                    'tanggal_rekapitulasi' => $tanggalRekapitulasi,
                    'saldo_pemasukkan' => $subTotal,
                    'total_penjualan' => $kuantitas
                ]);

                $keuangan = Keuangan::create([
                    'tgl_rekapitulasi' => $tanggalRekapitulasi,
                    'saldo_pengeluaran' => 0,
                    'saldo_pemasukkan' => $subTotal,
                    'total_penjualan' => $kuantitas,
                    'id_transaksi' => $transaksi->id,
                ]);

                if (!$keuangan) {
                    throw new \Exception('Failed to create new Keuangan record');
                }

                Log::info('Successfully created new Keuangan', [
                    'keuangan_id' => $keuangan->id,
                    'saldo_pemasukkan' => $keuangan->saldo_pemasukkan,
                    'total_penjualan' => $keuangan->total_penjualan
                ]);
            }

            $keuangan = $keuangan->fresh();

            if (!$transaksi->id_keuangan) {
                $transaksi->update(['id_keuangan' => $keuangan->id]);
                Log::info('Updated transaksi with keuangan_id', [
                    'transaksi_id' => $transaksi->id,
                    'keuangan_id' => $keuangan->id
                ]);
            }


            $finalKeuangan = Keuangan::find($keuangan->id);
            Log::info('Final Keuangan verification', [
                'keuangan_id' => $finalKeuangan->id,
                'final_saldo_pemasukkan' => $finalKeuangan->saldo_pemasukkan,
                'final_total_penjualan' => $finalKeuangan->total_penjualan,
                'tgl_rekapitulasi' => $finalKeuangan->tgl_rekapitulasi
            ]);

            return $keuangan;

        } catch (\Exception $e) {
            Log::error('Failed to updateOrCreateKeuangan', [
                'transaksi_id' => $transaksi ? $transaksi->id : 'null',
                'sub_total' => $subTotal ?? 'null',
                'kuantitas' => $kuantitas ?? 'null',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
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

        return view('pengepul.transaksi.payment', compact('paymentData'));
    }

    public function updatePaymentStatus($transaksiId, $paymentStatus = 'success', $statusTransaksiId = null)
    {
        try {
            $transaksi = Transaksi::with(['detailTransaksi', 'metodePembayaran'])
                ->findOrFail($transaksiId);

            $validPaymentStatuses = ['pending', 'success', 'failed', 'cancelled'];
            if (!in_array($paymentStatus, $validPaymentStatuses)) {
                throw new \InvalidArgumentException('Invalid payment status');
            }

            if ($transaksi->payment_status === $paymentStatus) {
                Log::info('Payment status already set', [
                    'transaksi_id' => $transaksiId,
                    'current_status' => $transaksi->payment_status,
                    'requested_status' => $paymentStatus
                ]);

                return [
                    'success' => true,
                    'message' => 'Payment status already set',
                    'transaksi' => $transaksi
                ];
            }

            if ($statusTransaksiId === null) {
                switch ($paymentStatus) {
                    case 'success':
                        $statusTransaksiId = 2;
                        break;
                    case 'pending':
                        $statusTransaksiId = 1;
                        break;
                    case 'failed':
                    case 'cancelled':
                        $statusTransaksiId = 1;
                        break;
                    default:
                        $statusTransaksiId = 1;
                }
            }

            DB::beginTransaction();

            try {
                $transaksi->update([
                    'payment_status' => $paymentStatus,
                    'id_status_transaksi' => $statusTransaksiId,
                ]);

                if ($paymentStatus === 'success' && $this->isDigitalPayment($transaksi->metodePembayaran->nama_metode)) {
                    $detail = $transaksi->detailTransaksi->first();

                    if ($detail) {
                        try {
                            $this->updateOrCreateKeuangan($transaksi, $detail->sub_total, $detail->kuantitas);

                            Log::info('Keuangan berhasil diupdate untuk pembayaran digital', [
                                'transaksi_id' => $transaksiId,
                                'sub_total' => $detail->sub_total,
                                'kuantitas' => $detail->kuantitas,
                                'metode_pembayaran' => $transaksi->metodePembayaran->nama_metode,
                                'payment_status' => $paymentStatus
                            ]);
                        } catch (\Exception $keuanganError) {
                            Log::error('Error updating keuangan after payment success', [
                                'transaksi_id' => $transaksiId,
                                'error' => $keuanganError->getMessage(),
                                'trace' => $keuanganError->getTraceAsString()
                            ]);
                        }
                    } else {
                        Log::warning('Detail transaksi tidak ditemukan untuk update keuangan', [
                            'transaksi_id' => $transaksiId
                        ]);
                    }
                }

                if (in_array($paymentStatus, ['failed', 'cancelled'])) {
                    $detail = $transaksi->detailTransaksi->first();
                    if ($detail) {
                        $stokDistribusi = StokDistribusi::find($detail->id_stok_distribusi);
                        if ($stokDistribusi) {
                            $stokDistribusi->increment('jumlah_stok', $detail->kuantitas);
                            Log::info('Stok dikembalikan karena pembayaran gagal/dibatalkan', [
                                'transaksi_id' => $transaksiId,
                                'stok_id' => $stokDistribusi->id,
                                'kuantitas' => $detail->kuantitas,
                                'payment_status' => $paymentStatus
                            ]);
                        }
                    }
                }

                DB::commit();

                Log::info('Payment status updated successfully', [
                    'transaksi_id' => $transaksiId,
                    'old_payment_status' => $transaksi->getOriginal('payment_status'),
                    'new_payment_status' => $paymentStatus,
                    'status_transaksi_id' => $statusTransaksiId
                ]);

                return [
                    'success' => true,
                    'message' => 'Payment status updated successfully',
                    'transaksi' => $transaksi->fresh()
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Failed to update payment status', [
                'transaksi_id' => $transaksiId,
                'payment_status' => $paymentStatus,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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

    public function markPaymentAsFailed($transaksiId)
    {
        return $this->updatePaymentStatus($transaksiId, 'failed', 1);
    }

    public function handlePaymentCallback(Request $request)
    {
        try {
            $this->initializeMidtrans();
            $notification = new Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;

            Log::info('Payment callback received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'notification_data' => $request->all()
            ]);

            $transaksi = Transaksi::where('order_id', $orderId)->first();

            if (!$transaksi) {
                Log::error('Transaction not found for order_id: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
            }

            if ($transaksi->payment_status === 'success') {
                Log::info('Transaction already processed as success', [
                    'transaksi_id' => $transaksi->id,
                    'order_id' => $orderId
                ]);
                return response()->json(['status' => 'success', 'message' => 'Transaction already processed']);
            }

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
            }
            else if ($transactionStatus == 'settlement') {
                $paymentStatus = 'success';
                $statusTransaksiId = 2;
            }
            else if ($transactionStatus == 'pending') {
                $paymentStatus = 'pending';
                $statusTransaksiId = 1;
            }
            else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $paymentStatus = 'failed';
                $statusTransaksiId = 4;
            }

            $result = $this->updatePaymentStatus($transaksi->id, $paymentStatus, $statusTransaksiId);

            if ($result['success']) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment processed successfully',
                    'payment_status' => $paymentStatus
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $result['message']
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Payment callback processing failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Callback processing failed: ' . $e->getMessage()
            ], 500);
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

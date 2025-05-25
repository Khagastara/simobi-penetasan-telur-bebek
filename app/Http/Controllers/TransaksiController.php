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

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['pengepul', 'detailTransaksi.stokDistribusi', 'statusTransaksi'])
            ->orderBy('tgl_transaksi', 'desc')
            ->get()
            ->map(function ($transaksi) {
                $latestStatus = $transaksi->statusTransaksi()
                    ->orderBy('id', 'desc')
                    ->first();

                $detail = $transaksi->detailTransaksi->first();

                return [
                    'id' => $transaksi->id,
                    'tgl_transaksi' => $transaksi->tgl_transaksi->format('d-m-Y H:i:s'),
                    'username' => $transaksi->pengepul->nama,
                    'nama_stok' => $detail ? $detail->stokDistribusi->nama_stok : 'N/A',
                    'kuantitas' => $detail ? $detail->kuantitas : 0,
                    'total_transaksi' => $detail ? $detail->sub_total : 0,
                    'status' => $transaksi->statusTransaksi ? $transaksi->statusTransaksi->nama_status : 'Menunggu Pembayaran',
                ];
            });

        return view('owner.transaksi.index', compact('transaksis'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['pengepul', 'detailTransaksi.stokDistribusi', 'metodePembayaran', 'statusTransaksi'])
            ->findOrFail($id);

        $latestStatus = $transaksi->statusTransaksi()
            ->orderBy('id', 'desc')
            ->first();

        $detail = $transaksi->detailTransaksi->first();

        $transaksiDetail = [
            'id' => $transaksi->id,
            'username' => $transaksi->pengepul->nama,
            'nama_stok' => $detail ? $detail->stokDistribusi->nama_stok : 'N/A',
            'kuantitas' => $detail ? $detail->kuantitas : 0,
            'total_transaksi' => $detail ? $detail->sub_total : 0,
            'metode_pembayaran' => $transaksi->metodePembayaran->nama_metode,
            'tanggal_transaksi' => $transaksi->tgl_transaksi->format('d-m-Y H:i:s'),
            'status' => $latestStatus ? $latestStatus->nama_status : 'Menunggu Pembayaran',
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
                    'status' => $latestStatus ? $latestStatus->nama_status : 'Menunggu Pembayaran',
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
            'status' => $latestStatus ? $latestStatus->nama_status : 'Menunggu Pembayaran',
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

    public function store(Request $request, $stokId)
    {
        $validator = Validator::make($request->all(), [
            'kuantitas' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|exists:metode_pembayarans,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'kuantitas harus berisikan angka')->withInput();
        }

        $stokDistribusi = StokDistribusi::findOrFail($stokId);

        if ($stokDistribusi->jumlah_stok < $request->kuantitas) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi')->withInput();
        }

        $subTotal = $stokDistribusi->harga_stok * $request->kuantitas;

        DB::beginTransaction();

        try {
            $transaksi = Transaksi::create([
                'tgl_transaksi' => now(),
                'id_pengepul' => Auth::user()->pengepul->id,
                'id_metode_pembayaran' => $request->metode_pembayaran,
            ]);

            DetailTransaksi::create([
                'kuantitas' => $request->kuantitas,
                'sub_total' => $subTotal,
                'id_transaksi' => $transaksi->id,
                'id_stok_distribusi' => $stokDistribusi->id,
            ]);

            StatusTransaksi::create([
                'nama_status' => 'Menunggu Pembayaran',
                'id_transaksi' => $transaksi->id,
            ]);
            $stokDistribusi->update([
                'jumlah_stok' => $stokDistribusi->jumlah_stok - $request->kuantitas,
            ]);

            Keuangan::create([
                'tgl_rekapitulasi' => now()->toDateString(),
                'saldo_pengeluaran' => 0,
                'saldo_pemasukkan' => $subTotal,
                'total_penjualan' => $request->kuantitas,
                'id_transaksi' => $transaksi->id,
            ]);

            DB::commit();
            $metodePembayaran = MetodePembayaran::find($request->metode_pembayaran);
            if ($metodePembayaran->nama_metode == 'Transfer') {
                return redirect()->route('pengepul.stok.index')->with('success', 'Pesanan berhasil dibuat, silahkan melakukan pembayaran');
            } else {
                return redirect()->route('pengepul.stok.index')->with('success', 'Pesanan berhasil dibuat');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.')->withInput();
        }
    }
}

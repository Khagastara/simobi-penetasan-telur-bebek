<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KeuanganController extends Controller
{
    public function index()
    {
        $keuangans = Keuangan::paginate(10);

        $groupedKeuangans = $keuangans->groupBy('tgl_rekapitulasi')->map(function ($items) {
            return [
                'saldo_pemasukkan' => $items->sum('saldo_pemasukkan'),
                'saldo_pengeluaran' => $items->sum('saldo_pengeluaran'),
            ];
        });

        $keuanganLabels = $groupedKeuangans->keys()->toArray();
        $keuanganPemasukkan = $groupedKeuangans->pluck('saldo_pemasukkan')->toArray();
        $keuanganPengeluaran = $groupedKeuangans->pluck('saldo_pengeluaran')->toArray();

        $tanggalRekapitulasi = Transaksi::select('tgl_transaksi')
            ->distinct()
            ->orderBy('tgl_transaksi', 'desc')
            ->get();

        return view('owner.keuangan.index', compact('keuangans', 'keuanganLabels', 'keuanganPemasukkan', 'keuanganPengeluaran', 'tanggalRekapitulasi'));
    }

    public function create()
    {
        $tanggalRekapitulasi = Transaksi::select('tgl_transaksi')
            ->distinct()
            ->orderBy('tgl_transaksi', 'desc')
            ->get();

        return view('owner.keuangan.create', compact('tanggalRekapitulasi'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl_rekapitulasi' => 'required|date',
            'saldo_pengeluaran' => 'required|integer',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Saldo pengeluaran harus berisikan angka');
        }

        Keuangan::updateOrCreate(
            ['tgl_rekapitulasi' => $request->tgl_rekapitulasi],
            [
                'saldo_pengeluaran' => $request->saldo_pengeluaran,
                'saldo_pemasukkan' => $this->calculateSaldoPemasukkan($request->tgl_rekapitulasi),
                'total_penjualan' => $this->calculateTotalPenjualan($request->tgl_rekapitulasi),
                'id_transaksi' => $request->id_transaksi,
            ]
        );

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('owner.keuangan.index')->with('success', 'Data keuangan berhasil dibuat');
    }

    public function show($id)
    {
        $keuangan = Keuangan::findOrFail($id);
        return view('owner.keuangan.show', compact('keuangan'));
    }

    public function edit($id)
    {
        $keuangan = Keuangan::findOrFail($id);
        return view('owner.keuangan.edit', compact('keuangan'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tgl_rekapitulasi' => 'required|date',
            'saldo_pengeluaran' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Saldo pengeluaran harus berisikan angka');
        }

        $keuangan = Keuangan::findOrFail($id);
        $keuangan->update([
            'tgl_rekapitulasi' => $request->tgl_rekapitulasi,
            'saldo_pengeluaran' => $request->saldo_pengeluaran,
            'saldo_pemasukkan' => $this->calculateSaldoPemasukkan($request->tgl_rekapitulasi),
            'total_penjualan' => $this->calculateTotalPenjualan($request->tgl_rekapitulasi),
            'id_transaksi' => $request->id_transaksi,
        ]);

        return redirect()->route('owner.keuangan.show', $id)->with('success', 'Data keuangan berhasil diubah');
    }

    private function calculateSaldoPemasukkan($date)
    {
        return Transaksi::whereDate('tgl_transaksi', $date)
            ->with('detailTransaksi')
            ->get()
            ->sum(function ($transaksi) {
                return $transaksi->detailTransaksi->sum('sub_total');
            });
    }

    private function calculateTotalPenjualan($date)
    {
        return Transaksi::whereDate('tgl_transaksi', $date)
            ->with('detailTransaksi')
            ->get()
            ->sum(function ($transaksi) {
                return $transaksi->detailTransaksi->sum('kuantitas');
            });
    }


    private function generateGrafikPenjualan($date)
    {
        $transactions = Transaksi::whereDate('tgl_transaksi', $date)
            ->with('detailTransaksi')
            ->get();

        $data = $transactions->map(function ($transaksi) {
            return [
                'tanggal' => $transaksi->tgl_transaksi->format('Y-m-d'),
                'saldo_pemasukkan' => $transaksi->detailTransaksi->sum('sub_total'),
                'saldo_pengeluaran' => 0,
            ];
        });

        return json_encode($data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KeuanganController extends Controller
{
    /**
     * Display a listing of the keuangan data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $keuangans = Keuangan::all();

        // Kelompokkan data berdasarkan tanggal
        $groupedKeuangans = $keuangans->groupBy('tgl_rekapitulasi')->map(function ($items) {
            return [
                'saldo_pemasukkan' => $items->sum('saldo_pemasukkan'),
                'saldo_pengeluaran' => $items->sum('saldo_pengeluaran'),
            ];
        });

        // Siapkan data untuk grafik
        $keuanganLabels = $groupedKeuangans->keys()->toArray(); // Tanggal sebagai label sumbu X
        $keuanganPemasukkan = $groupedKeuangans->pluck('saldo_pemasukkan')->toArray(); // Total saldo pemasukkan
        $keuanganPengeluaran = $groupedKeuangans->pluck('saldo_pengeluaran')->toArray(); // Total saldo pengeluaran

        return view('owner.keuangan.index', compact('keuangans', 'keuanganLabels', 'keuanganPemasukkan', 'keuanganPengeluaran'));
    }

    /**
     * Show the form for creating a new keuangan entry.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $transaksis = Transaksi::select('id', 'tgl_transaksi')
            ->orderBy('tgl_transaksi', 'desc')
            ->get();

        return view('owner.keuangan.create', compact('transaksis'));
    }

    /**
     * Store a newly created keuangan entry in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl_rekapitulasi' => 'required|date',
            'saldo_pengeluaran' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Saldo pengeluaran harus berisikan angka');
        }

        Keuangan::create([
            'tgl_rekapitulasi' => $request->tgl_rekapitulasi,
            'saldo_pengeluaran' => $request->saldo_pengeluaran,
            'saldo_pemasukkan' => $this->calculateSaldoPemasukkan($request->tgl_rekapitulasi),
            'total_penjualan' => $this->calculateTotalPenjualan($request->tgl_rekapitulasi),
            'id_transaksi' => $request->id_transaksi,
        ]);

        return redirect()->route('owner.keuangan.index')->with('success', 'Data keuangan berhasil dibuat');
    }

    /**
     * Display the specified keuangan entry.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $keuangan = Keuangan::findOrFail($id);
        return view('owner.keuangan.show', compact('keuangan'));
    }

    /**
     * Show the form for editing the specified keuangan entry.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $keuangan = Keuangan::findOrFail($id);
        return view('owner.keuangan.edit', compact('keuangan'));
    }

    /**
     * Update the specified keuangan entry in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Calculate saldo pemasukkan based on transactions.
     *
     * @param  string  $date
     * @return int
     */
    private function calculateSaldoPemasukkan($date)
    {
        return Transaksi::whereDate('tgl_transaksi', $date)
            ->with('detailTransaksi')
            ->get()
            ->sum(function ($transaksi) {
                return $transaksi->detailTransaksi->sum('sub_total');
            });
    }

    /**
     * Calculate total penjualan based on transactions.
     *
     * @param  string  $date
     * @return int
     */
    private function calculateTotalPenjualan($date)
    {
        return Transaksi::whereDate('tgl_transaksi', $date)
            ->with('detailTransaksi')
            ->get()
            ->sum(function ($transaksi) {
                return $transaksi->detailTransaksi->sum('kuantitas');
            });
    }

    /**
     * Generate grafik penjualan data.
     *
     * @param  string  $date
     * @return string
     */
    private function generateGrafikPenjualan($date)
    {
        $transactions = Transaksi::whereDate('tgl_transaksi', $date)
            ->with('detailTransaksi')
            ->get();

        $data = $transactions->map(function ($transaksi) {
            return [
                'tanggal' => $transaksi->tgl_transaksi->format('Y-m-d'),
                'saldo_pemasukkan' => $transaksi->detailTransaksi->sum('sub_total'),
                'saldo_pengeluaran' => 0, // Placeholder, adjust if needed
            ];
        });

        return json_encode($data);
    }
}

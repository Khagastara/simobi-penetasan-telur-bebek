<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = $request->get('current_year', now()->year);

        if ($request->get('direction') === 'prev') {
            $currentYear--;
        } elseif ($request->get('direction') === 'next') {
            $currentYear++;
        }

        $startOfYear = Carbon::create($currentYear, 1, 1)->startOfDay();
        $endOfYear = Carbon::create($currentYear, 12, 31)->endOfDay();

        $keuangans = Keuangan::whereYear('tgl_rekapitulasi', $currentYear)
            ->orderBy('tgl_rekapitulasi', 'asc')
            ->get();

        $keuanganLabels = [];
        $keuanganPemasukkan = [];
        $keuanganPengeluaran = [];
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        for ($month = 1; $month <= 12; $month++) {
            $monthData = $keuangans->filter(function ($item) use ($month) {
                return Carbon::parse($item->tgl_rekapitulasi)->month == $month;
            });

            $keuanganLabels[] = $monthNames[$month];
            $keuanganPemasukkan[] = $monthData->sum('saldo_pemasukkan');
            $keuanganPengeluaran[] = $monthData->sum('saldo_pengeluaran');
        }

        $totalPemasukan = $keuangans->sum('saldo_pemasukkan');
        $totalPengeluaran = $keuangans->sum('saldo_pengeluaran');

        $tanggalRekapitulasi = Transaksi::select('tgl_transaksi')
            ->distinct()
            ->orderBy('tgl_transaksi', 'desc')
            ->get();

        return view('owner.keuangan.index', compact(
            'keuangans',
            'keuanganLabels',
            'keuanganPemasukkan',
            'keuanganPengeluaran',
            'tanggalRekapitulasi',
            'currentYear',
            'totalPemasukan',
            'totalPengeluaran'
        ));
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
            'saldo_pengeluaran' => 'required|integer|min:0',
        ], [
            'saldo_pengeluaran.required' => 'Saldo pengeluaran wajib diisi',
            'saldo_pengeluaran.integer' => 'Saldo pengeluaran harus berupa angka',
            'saldo_pengeluaran.min' => 'Saldo pengeluaran tidak boleh kurang dari 0',
            'tgl_rekapitulasi.required' => 'Tanggal rekapitulasi wajib diisi',
            'tgl_rekapitulasi.date' => 'Format tanggal tidak valid',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $existingKeuangan = Keuangan::where('tgl_rekapitulasi', $request->tgl_rekapitulasi)->first();

        $saldoPemasukkan = $this->calculateSaldoPemasukkan($request->tgl_rekapitulasi);
        $totalPenjualan = $this->calculateTotalPenjualan($request->tgl_rekapitulasi);

        if ($existingKeuangan) {
            $newSaldoPengeluaran = $existingKeuangan->saldo_pengeluaran + $request->saldo_pengeluaran;

            $existingKeuangan->update([
                'saldo_pengeluaran' => $newSaldoPengeluaran,
                'saldo_pemasukkan' => $saldoPemasukkan,
                'total_penjualan' => $totalPenjualan,
            ]);

            $message = 'Data keuangan berhasil diperbarui. Saldo pengeluaran ditambahkan ke data yang sudah ada.';
            $action = 'updated';
        } else {
            $keuangan = Keuangan::create([
                'tgl_rekapitulasi' => $request->tgl_rekapitulasi,
                'saldo_pengeluaran' => $request->saldo_pengeluaran,
                'saldo_pemasukkan' => $saldoPemasukkan,
                'total_penjualan' => $totalPenjualan,
            ]);

            $message = 'Data keuangan berhasil ditambahkan';
            $action = 'created';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'action' => $action
            ]);
        }

        return redirect()->route('owner.keuangan.index')
            ->with('success', $message);
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
            'saldo_pengeluaran' => 'required|integer|min:0',
        ], [
            'saldo_pengeluaran.required' => 'Saldo pengeluaran wajib diisi',
            'saldo_pengeluaran.integer' => 'Saldo pengeluaran harus berupa angka',
            'saldo_pengeluaran.min' => 'Saldo pengeluaran tidak boleh kurang dari 0',
            'tgl_rekapitulasi.required' => 'Tanggal rekapitulasi wajib diisi',
            'tgl_rekapitulasi.date' => 'Format tanggal tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $keuangan = Keuangan::findOrFail($id);

        $existingKeuangan = Keuangan::where('tgl_rekapitulasi', $request->tgl_rekapitulasi)
            ->where('id', '!=', $id)
            ->first();

        if ($existingKeuangan) {
            return redirect()->back()
                ->withErrors(['tgl_rekapitulasi' => 'Data keuangan untuk tanggal ini sudah ada'])
                ->withInput();
        }

        $keuangan->update([
            'tgl_rekapitulasi' => $request->tgl_rekapitulasi,
            'saldo_pengeluaran' => $request->saldo_pengeluaran,
            'saldo_pemasukkan' => $this->calculateSaldoPemasukkan($request->tgl_rekapitulasi),
            'total_penjualan' => $this->calculateTotalPenjualan($request->tgl_rekapitulasi),
        ]);

        return redirect()->route('owner.keuangan.index')
            ->with('success', 'Data keuangan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $keuangan = Keuangan::findOrFail($id);
        $keuangan->delete();

        return redirect()->route('owner.keuangan.index')
            ->with('success', 'Data keuangan berhasil dihapus');
    }

    /**
     * Hitung saldo pemasukkan berdasarkan transaksi pada tanggal tertentu
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
     * Hitung total penjualan berdasarkan kuantitas pada tanggal tertentu
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

    public function getKeuanganByPeriod(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $keuangans = Keuangan::whereBetween('tgl_rekapitulasi', [$startDate, $endDate])
            ->orderBy('tgl_rekapitulasi', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $keuangans,
            'summary' => [
                'total_pemasukan' => $keuangans->sum('saldo_pemasukkan'),
                'total_pengeluaran' => $keuangans->sum('saldo_pengeluaran'),
                'selisih' => $keuangans->sum('saldo_pemasukkan') - $keuangans->sum('saldo_pengeluaran')
            ]
        ]);
    }
}

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
        // Ambil tanggal saat ini atau dari request
        $currentDate = $request->get('current_date', now()->format('Y-m-d'));
        $date = Carbon::parse($currentDate);

        // Tentukan periode mingguan saat ini (Senin sampai Minggu)
        $startOfWeek = $date->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $date->copy()->endOfWeek(Carbon::SUNDAY);

        // Handle navigasi periode (prev/next)
        if ($request->get('direction') === 'prev') {
            $startOfWeek = $startOfWeek->subWeek();
            $endOfWeek = $endOfWeek->subWeek();
        } elseif ($request->get('direction') === 'next') {
            $startOfWeek = $startOfWeek->addWeek();
            $endOfWeek = $endOfWeek->addWeek();
        }

        // Format periode untuk tampilan
        $periodeStart = $startOfWeek->format('d M Y');
        $periodeEnd = $endOfWeek->format('d M Y');

        // Tanggal untuk navigasi (tanggal tengah minggu untuk konsistensi)
        $navigationDate = $startOfWeek->copy()->addDays(3)->format('Y-m-d');

        // Ambil data keuangan untuk periode mingguan
        $keuangans = Keuangan::whereBetween('tgl_rekapitulasi', [
            $startOfWeek->format('Y-m-d'),
            $endOfWeek->format('Y-m-d')
        ])->orderBy('tgl_rekapitulasi', 'asc')->get();

        // Siapkan data untuk grafik
        $keuanganLabels = [];
        $keuanganPemasukkan = [];
        $keuanganPengeluaran = [];

        // Generate data untuk setiap hari dalam minggu
        for ($i = 0; $i < 7; $i++) {
            $currentDay = $startOfWeek->copy()->addDays($i);
            $dayData = $keuangans->where('tgl_rekapitulasi', $currentDay->format('Y-m-d'))->first();

            $keuanganLabels[] = $currentDay->format('d/m');
            $keuanganPemasukkan[] = $dayData ? $dayData->saldo_pemasukkan : 0;
            $keuanganPengeluaran[] = $dayData ? $dayData->saldo_pengeluaran : 0;
        }

        // Hitung total untuk periode
        $totalPemasukan = $keuangans->sum('saldo_pemasukkan');
        $totalPengeluaran = $keuangans->sum('saldo_pengeluaran');

        // Ambil tanggal rekapitulasi untuk dropdown (jika diperlukan)
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
            'periodeStart',
            'periodeEnd',
            'totalPemasukan',
            'totalPengeluaran',
            'navigationDate'
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

        // Cek apakah sudah ada data untuk tanggal tersebut
        $existingKeuangan = Keuangan::where('tgl_rekapitulasi', $request->tgl_rekapitulasi)->first();

        if ($existingKeuangan) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['tgl_rekapitulasi' => ['Data keuangan untuk tanggal ini sudah ada']]
                ]);
            }
            return redirect()->back()->withErrors(['tgl_rekapitulasi' => 'Data keuangan untuk tanggal ini sudah ada'])->withInput();
        }

        $keuangan = Keuangan::create([
            'tgl_rekapitulasi' => $request->tgl_rekapitulasi,
            'saldo_pengeluaran' => $request->saldo_pengeluaran,
            'saldo_pemasukkan' => $this->calculateSaldoPemasukkan($request->tgl_rekapitulasi),
            'total_penjualan' => $this->calculateTotalPenjualan($request->tgl_rekapitulasi),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data keuangan berhasil ditambahkan'
            ]);
        }

        return redirect()->route('owner.keuangan.index')
            ->with('success', 'Data keuangan berhasil ditambahkan');
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

        // Cek apakah ada data lain dengan tanggal yang sama (kecuali data yang sedang diedit)
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

    /**
     * Ambil data keuangan untuk periode tertentu (untuk API atau AJAX)
     */
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

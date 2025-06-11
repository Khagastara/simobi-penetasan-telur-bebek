<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\PenjadwalanKegiatan;
use App\Models\DetailPenjadwalan;
use App\Models\StatusKegiatan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $owner = Auth::user()->owner;

        $currentDate = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::now();

        $startDate = $currentDate->copy()->startOfMonth();
        $endDate = $currentDate->copy()->endOfMonth();

        $totalPemasukan = Keuangan::whereBetween('tgl_rekapitulasi', [$startDate, $endDate])
            ->sum('saldo_pemasukkan');

        $totalPengeluaran = Keuangan::whereBetween('tgl_rekapitulasi', [$startDate, $endDate])
            ->sum('saldo_pengeluaran');

        $selisihKeuangan = $totalPemasukan - $totalPengeluaran;

        $jadwalGagal = DetailPenjadwalan::join('penjadwalan_kegiatans', 'detail_penjadwalans.id_penjadwalan', '=', 'penjadwalan_kegiatans.id')
            ->where('penjadwalan_kegiatans.id_owner', $owner->id)
            ->whereBetween('penjadwalan_kegiatans.tgl_penjadwalan', [$startDate, $endDate])
            ->where('detail_penjadwalans.id_status_kegiatan', 3)
            ->count();

        $transaksiPending = 0;
        try {
            $transaksiPending = Transaksi::where('payment_status', 'pending')
                ->whereHas('owner', function($query) use ($owner) {
                    $query->where('id', $owner->id);
                })
                ->count();
        } catch (\Exception $e) {
            Log::warning('Failed to count pending transactions: ' . $e->getMessage());
        }

        $dashboardData = [
            'selisih_keuangan' => $selisihKeuangan,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'jadwal_gagal' => $jadwalGagal,
            'periode_start' => $startDate->format('d M Y'),
            'periode_end' => $endDate->format('d M Y'),
        ];

        return view('owner.dashboard', compact('dashboardData', 'transaksiPending'));
    }

    public function getFinancialData(Request $request)
    {
        $owner = Auth::user()->owner;
        $currentDate = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::now();

        $startDate = $currentDate->copy()->startOfMonth();
        $endDate = $currentDate->copy()->endOfMonth();

        $keuanganHarian = Keuangan::whereBetween('tgl_rekapitulasi', [$startDate, $endDate])
            ->orderBy('tgl_rekapitulasi')
            ->get();

        $totalPemasukan = $keuanganHarian->sum('saldo_pemasukkan');
        $totalPengeluaran = $keuanganHarian->sum('saldo_pengeluaran');

        $jadwalGagal = DetailPenjadwalan::join('penjadwalan_kegiatan', 'detail_penjadwalan.id_penjadwalan', '=', 'penjadwalan_kegiatan.id')
            ->where('penjadwalan_kegiatan.id_owner', $owner->id)
            ->whereBetween('penjadwalan_kegiatan.tgl_penjadwalan', [$startDate, $endDate])
            ->where('detail_penjadwalan.id_status_kegiatan', 3)
            ->count();

        return response()->json([
            'keuangan_harian' => $keuanganHarian,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'selisih' => $totalPemasukan - $totalPengeluaran,
            'jadwal_gagal' => $jadwalGagal,
            'periode' => [
                'start' => $startDate->format('d M Y'),
                'end' => $endDate->format('d M Y')
            ]
        ]);
    }

    public function changeMonth(Request $request)
    {
        $direction = $request->get('direction');
        $currentDate = $request->get('current_date') ? Carbon::parse($request->get('current_date')) : Carbon::now();

        if ($direction === 'prev') {
            $newDate = $currentDate->subMonth();
        } else {
            $newDate = $currentDate->addMonth();
        }

        return redirect()->route('owner.dashboard', ['date' => $newDate->format('Y-m-d')]);
    }
}

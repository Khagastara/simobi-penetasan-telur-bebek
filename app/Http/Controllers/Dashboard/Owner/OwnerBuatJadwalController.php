<?php

namespace App\Http\Controllers\Dashboard\Owner;

use App\Http\Controllers\Controller;
use App\Models\PenjadwalanKegiatan;
use App\Models\DetailPenjadwalan;
use App\Models\StatusKegiatan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OwnerBuatjadwalController extends Controller
{
    // Step c-d: Show calendar view
    public function index()
    {
        $schedules = PenjadwalanKegiatan::with(['details.status'])
            ->orderBy('tgl_penjadwalan')
            ->get()
            ->groupBy('tgl_penjadwalan');

        return view('penjadwalan.index', [
            'groupedSchedules' => $schedules,
            'statusOptions' => StatusKegiatan::all()
        ]);
    }

    // Step e-f: Show date details
    public function showDate($date)
    {
        $dateSchedules = PenjadwalanKegiatan::with(['details.status'])
            ->where('tgl_penjadwalan', $date)
            ->get();

        return view('penjadwalan.date-details', [
            'date' => Carbon::parse($date),
            'schedules' => $dateSchedules
        ]);
    }

    // Step g-j: Store new schedule
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_penjadwalan' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_kegiatan' => 'required|date_format:H:i',
            'keterangan' => 'required|string',
            'id_owner' => 'required|exists:owners,id'
        ]);

        $schedule = PenjadwalanKegiatan::create([
            'tgl_penjadwalan' => $validated['tgl_penjadwalan'],
            'id_owner' => $validated['id_owner']
        ]);

        DetailPenjadwalan::create([
            'id_penjadwalan' => $schedule->id,
            'waktu_kegiatan' => $validated['waktu_kegiatan'],
            'keterangan' => $validated['keterangan'],
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'id_status_kegiatan' => StatusKegiatan::where('nama_status_kgtn', 'To Do')->first()->id
        ]);

        return redirect()->route('penjadwalan.index')
            ->with('success', 'Jadwal berhasil dibuat');
    }

    // Update schedule status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:status_kegiatans,id'
        ]);

        $detail = DetailPenjadwalan::findOrFail($id);
        $detail->update(['id_status_kegiatan' => $request->status_id]);

        return back()->with('success', 'Status kegiatan diperbarui');
    }

    // Additional helper method for calendar
    private function getCalendarData()
    {
        return PenjadwalanKegiatan::selectRaw('
            YEAR(tgl_penjadwalan) as year,
            MONTH(tgl_penjadwalan) as month,
            DAY(tgl_penjadwalan) as day,
            COUNT(*) as count
        ')
        ->groupBy('year', 'month', 'day')
        ->get()
        ->mapWithKeys(function ($item) {
            $date = Carbon::create($item->year, $item->month, $item->day);
            return [$date->format('Y-m-d') => $item->count];
        });
    }
}

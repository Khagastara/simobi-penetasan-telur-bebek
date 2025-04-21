<?php

namespace App\Http\Controllers\Dashboard\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\OwnerBuatJadwalRequest;
use App\Http\Requests\Owner\OwnerUbahJadwalRequest;
use App\Http\Requests\Owner\OwnerUbahStatusRequest;
use App\Models\PenjadwalanKegiatan;
use App\Models\DetailPenjadwalan;
use App\Models\StatusKegiatan;
use Carbon\Carbon;

class PenjadwalanController extends Controller
{
    public function index()
    {
        $schedules = PenjadwalanKegiatan::with(['details.status'])
            ->orderBy('tgl_penjadwalan')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->tgl_penjadwalan)->format('Y-m-d');
            });

        return view('dashboard.penjadwalan.index', [
            'groupedSchedules' => $schedules,
            'statusOptions' => StatusKegiatan::all(),
            'calendarData' => $this->getCalendarData()
        ]);
    }

    public function showDate($date)
    {
        $date = Carbon::parse($date)->format('Y-m-d');

        $schedules = PenjadwalanKegiatan::with(['details.status'])
            ->where('tgl_penjadwalan', $date)
            ->get()
            ->flatMap(function ($item) {
                return $item->details;
            });

        return view('dashboard.penjadwalan.show-date', [
            'date' => Carbon::parse($date),
            'schedules' => $schedules
        ]);
    }

    public function create()
    {
        return view('dashboard.penjadwalan.create', [
            'statusOptions' => StatusKegiatan::all(),
            'availableDates' => PenjadwalanKegiatan::upcomingDates()
        ]);
    }

    public function store(OwnerBuatJadwalRequest $request)
    {
        $validated = $request->validated();

        $schedule = PenjadwalanKegiatan::create([
            'tgl_penjadwalan' => $validated['tgl_penjadwalan'],
            'id_owner' => $validated['id_owner']
        ]);

        DetailPenjadwalan::create([
            'id_penjadwalan' => $schedule->id,
            'waktu_kegiatan' => $validated['waktu_kegiatan'],
            'keterangan' => $validated['keterangan'],
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'id_status_kegiatan' => $validated['id_status_kegiatan'] ??
                StatusKegiatan::defaultStatusId()
        ]);

        return redirect()->route('dashboard.penjadwalan.index')
            ->with('success', 'Jadwal berhasil dibuat');
    }

    /**
     * Show form to edit existing schedule
     */
    public function edit($id)
    {
        $detail = DetailPenjadwalan::with(['penjadwalan', 'status'])
            ->findOrFail($id);

        return view('dashboard.penjadwalan.edit', [
            'detail' => $detail,
            'statusOptions' => StatusKegiatan::all(),
            'availableDates' => PenjadwalanKegiatan::upcomingDates()
        ]);
    }

    /**
     * Update existing schedule
     */
    public function update(OwnerUbahJadwalRequest $request, $id)
    {
        $validated = $request->validated();
        $detail = DetailPenjadwalan::findOrFail($id);

        // Update parent schedule if date changed
        if ($detail->penjadwalan->tgl_penjadwalan != $validated['tgl_penjadwalan']) {
            $detail->penjadwalan()->update([
                'tgl_penjadwalan' => $validated['tgl_penjadwalan']
            ]);
        }

        $detail->update([
            'waktu_kegiatan' => $validated['waktu_kegiatan'],
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'keterangan' => $validated['keterangan'],
            'id_status_kegiatan' => $validated['id_status_kegiatan']
        ]);

        return redirect()->route('dashboard.penjadwalan.show.date', $validated['tgl_penjadwalan'])
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    /**
     * Show single schedule detail
     */
    public function showDetail($id)
    {
        $detail = DetailPenjadwalan::with(['penjadwalan.owner', 'status'])
        ->findOrFail($id);

        return view('dashboard.penjadwalan.show-detail', [
            'detail' => $detail,
            'schedule' => $detail->penjadwalan
        ]);
    }

    /**
     * Update only the status of a schedule
     */
    public function updateStatus(OwnerUbahStatusRequest $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:status_kegiatans,id'
        ]);

        $detail = DetailPenjadwalan::findOrFail($id);
        $detail->update(['id_status_kegiatan' => $request->status_id]);

        return back()->with('success', 'Status kegiatan diperbarui');
    }

    /**
     * Get calendar data for visualization
     */
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

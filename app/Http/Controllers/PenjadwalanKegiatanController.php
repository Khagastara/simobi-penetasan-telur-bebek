<?php

namespace App\Http\Controllers;

use App\Models\PenjadwalanKegiatan;
use App\Models\DetailPenjadwalan;
use App\Models\StatusKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Pusher\PushNotifications\PushNotifications;
use Carbon\Carbon;

class PenjadwalanKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $owner = Auth::user()->owner;

        $filterMonth = $request->get('month');
        $filterYear = $request->get('year');

        $query = $owner->penjadwalanKegiatan()
            ->with(['detailPenjadwalan' => function($query) {
                $query->orderBy('waktu_kegiatan', 'desc');
            }, 'detailPenjadwalan.statusKegiatan'])
            ->orderBy('tgl_penjadwalan', 'desc')
            ->orderBy('id', 'desc');

        if ($filterMonth) {
            $query->whereMonth('tgl_penjadwalan', $filterMonth);
        }

        if ($filterYear) {
            $query->whereYear('tgl_penjadwalan', $filterYear);
        }

        $penjadwalanKegiatans = $query->paginate(10);

        $statusKegiatan = StatusKegiatan::all();

        $this->updateLateActivities($penjadwalanKegiatans);

        $availableYears = $owner->penjadwalanKegiatan()
            ->selectRaw('YEAR(tgl_penjadwalan) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $availableMonths = collect([
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ]);

        return view('owner.penjadwalan.index', compact(
            'penjadwalanKegiatans',
            'statusKegiatan',
            'availableYears',
            'availableMonths',
            'filterMonth',
            'filterYear'
        ));
    }

    private function updateLateActivities($penjadwalanKegiatans)
    {
        $gagalStatusId = StatusKegiatan::where('nama_status_kgtn', 'Gagal')->first()->id;
        $currentDateTime = Carbon::now();

        foreach ($penjadwalanKegiatans as $penjadwalan) {
            foreach ($penjadwalan->detailPenjadwalan as $detail) {
                if ($detail->statusKegiatan->nama_status_kgtn === 'To Do') {
                    $scheduledDateTime = Carbon::parse($penjadwalan->tgl_penjadwalan->format('Y-m-d') . ' ' . $detail->waktu_kegiatan);
                    $isLate = $currentDateTime->diffInMinutes($scheduledDateTime, false) < -30;

                    if ($isLate) {
                        $detail->update(['id_status_kegiatan' => $gagalStatusId]);
                        Log::info('Auto-updated late activity to Gagal:', [
                            'detail_id' => $detail->id,
                            'scheduled_time' => $scheduledDateTime->format('Y-m-d H:i'),
                            'current_time' => $currentDateTime->format('Y-m-d H:i')
                        ]);
                    }
                }
            }
        }
    }

    public function create()
    {
        $statusKegiatan = StatusKegiatan::all();
        return view('owner.penjadwalan.create', compact('statusKegiatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_penjadwalan' => 'required|date',
            'detail_penjadwalan.*.waktu_kegiatan' => 'required|date_format:H:i',
            'detail_penjadwalan.*.keterangan' => 'required|string|max:255',
            'detail_penjadwalan.*.id_status_kegiatan' => 'required|exists:status_kegiatans,id',
        ]);

        $penjadwalanKegiatan = PenjadwalanKegiatan::create([
            'tgl_penjadwalan' => $request->tgl_penjadwalan,
            'id_owner' => Auth::user()->owner->id,
        ]);
        Log::info('Penjadwalan created:', $penjadwalanKegiatan->toArray());

        foreach ($request->detail_penjadwalan as $detail) {
            $detailPenjadwalan = DetailPenjadwalan::create([
                'waktu_kegiatan' => $detail['waktu_kegiatan'],
                'keterangan' => $detail['keterangan'],
                'id_penjadwalan' => $penjadwalanKegiatan->id,
                'id_status_kegiatan' => $detail['id_status_kegiatan'],
            ]);
            Log::info('Detail created:', $detailPenjadwalan->toArray());
        }

        return redirect()->route('owner.penjadwalan.index')->with('success', 'Jadwal berhasil dibuat');
    }

    public function edit($id)
    {
        $penjadwalanKegiatan = PenjadwalanKegiatan::with('detailPenjadwalan')->findOrFail($id);
        $statusKegiatan = StatusKegiatan::all();

        return view('owner.penjadwalan.edit', compact('penjadwalanKegiatan', 'statusKegiatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_penjadwalan' => 'required|date',
            'detail_penjadwalan.*.waktu_kegiatan' => 'required|date_format:H:i',
            'detail_penjadwalan.*.keterangan' => 'required|string',
            'detail_penjadwalan.*.id_status_kegiatan' => 'required|exists:status_kegiatans,id',
        ]);

        $penjadwalanKegiatan = PenjadwalanKegiatan::findOrFail($id);
        $penjadwalanKegiatan->update([
            'tgl_penjadwalan' => $request->tgl_penjadwalan,
        ]);

        foreach ($request->detail_penjadwalan as $detail) {
            DetailPenjadwalan::updateOrCreate(
                ['id' => $detail['id']],
                [
                    'penjadwalan_kegiatan_id' => $penjadwalanKegiatan->id,
                    'waktu_kegiatan' => $detail['waktu_kegiatan'],
                    'keterangan' => $detail['keterangan'],
                    'id_status_kegiatan' => $detail['id_status_kegiatan'],
                ]
            );
        }

        return redirect()->route('owner.penjadwalan.index')->with('success', 'Jadwal updated successfully.');
    }

    public function show($id)
    {
        $penjadwalanKegiatan = PenjadwalanKegiatan::with('detailPenjadwalan')->findOrFail($id);

        if ($penjadwalanKegiatan->id_owner != Auth::user()->owner->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('owner.penjadwalan.show', compact('penjadwalanKegiatan'));
    }

    public function duration(Request $request, $id)
    {
        $detailPenjadwalan = DetailPenjadwalan::findOrFail($id);

        if ($request->status === 'Selesai') {
            $detailPenjadwalan->update(['id_status_kegiatan' => StatusKegiatan::where('nama_status_kgtn', 'Selesai')->first()->id]);
        } elseif ($request->status === 'Gagal') {
            $detailPenjadwalan->update(['id_status_kegiatan' => StatusKegiatan::where('nama_status_kgtn', 'Gagal')->first()->id]);
        }

        return redirect()->route('owner.penjadwalan.index')->with('success', 'Status kegiatan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $penjadwalanKegiatan = PenjadwalanKegiatan::findOrFail($id);

        if ($penjadwalanKegiatan->id_owner != Auth::user()->owner->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $penjadwalanKegiatan->detailPenjadwalan()->delete();

            $penjadwalanKegiatan->delete();

            return redirect()->route('owner.penjadwalan.index')->with('success', 'Jadwal berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Failed to delete penjadwalan: ' . $e->getMessage(), [
                'id' => $id
            ]);

            return redirect()->route('owner.penjadwalan.index')->with('error', 'Gagal menghapus jadwal');
        }
    }

    public function sendNotification(PenjadwalanKegiatan $penjadwalanKegiatan, DetailPenjadwalan $detailPenjadwalan)
    {
        try {
            $beamsClient = new PushNotifications([
                'instanceId' => config('pusher-beams.instance_id'),
                'secretKey' => config('pusher-beams.secret_key')
            ]);

            $formattedDate = Carbon::parse($penjadwalanKegiatan->tgl_penjadwalan)->format('Y-m-d');

            $response = $beamsClient->publishToInterests(
                ['owner-' . $penjadwalanKegiatan->id_owner],
                [
                    'web' => [
                        'notification' => [
                            'title' => 'Pengingat Kegiatan',
                            'body' => "Kegiatan: {$detailPenjadwalan->keterangan} pada pukul {$detailPenjadwalan->waktu_kegiatan} tanggal" .
                                $formattedDate . "!",
                        ],
                    ],
                ]
            );

            Log::info('Notification sent:', [
                'activity' => $detailPenjadwalan->keterangan,
                'date' => $formattedDate,
                'time' => $detailPenjadwalan->waktu_kegiatan,
                'owner_id' => $penjadwalanKegiatan->id_owner
            ]);

            if (request()->expectsJson()) {
                return response()->json($response);
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('Failed to send notification: ' . $e->getMessage(), [
                'activity_id' => $detailPenjadwalan->id,
                'schedule_id' => $penjadwalanKegiatan->id
            ]);

            if (request()->expectsJson()) {
                return response()->json(['error' => 'Failed to send notification'], 500);
            }

            throw $e;
        }
    }
}

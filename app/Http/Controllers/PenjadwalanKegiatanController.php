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
    public function index()
    {
        $owner = Auth::user()->owner;
        $penjadwalanKegiatans = $owner->penjadwalanKegiatan()->with('detailPenjadwalan.statusKegiatan')->get();
        $statusKegiatan = StatusKegiatan::all();
        return view('owner.penjadwalan.index', compact('penjadwalanKegiatans', 'statusKegiatan'));
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

        // Check if the current user is authorized to view this
        if ($penjadwalanKegiatan->id_owner != Auth::user()->owner->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('owner.penjadwalan.show', compact('penjadwalanKegiatan'));
    }

    public function sendNotification(PenjadwalanKegiatan $penjadwalanKegiatan, DetailPenjadwalan $detailPenjadwalan)
    {
        try {
            $beamsClient = new PushNotifications([
                'instanceId' => env('PUSHER_BEAMS_INSTANCE_ID'),
                'secretKey' => env('PUSHER_BEAMS_SECRET_KEY'),
            ]);

            $formattedDate = Carbon::parse($penjadwalanKegiatan->tgl_penjadwalan)->format('Y-m-d');

            $response = $beamsClient->publishToInterests(
                ['owner-' . $penjadwalanKegiatan->id_owner],
                [
                    'web' => [
                        'notification' => [
                            'title' => 'Pengingat Kegiatan',
                            'body' => "Kegiatan: {$detailPenjadwalan->keterangan} pada " .
                                $formattedDate .
                                " pukul {$detailPenjadwalan->waktu_kegiatan}.",
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

            // Return response in a format that works for both HTTP and CLI contexts
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

            throw $e; // Rethrow for CLI handling
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PenjadwalanKegiatan;
use App\Models\DetailPenjadwalan;
use App\Models\StatusKegiatan;
use App\Notifications\ActivityReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PenjadwalanKegiatanController extends Controller
{
    public function index()
    {
        $owner = Auth::user()->owner;
        $penjadwalanKegiatans = $owner->penjadwalanKegiatan()->with('detailPenjadwalan')->get();
        return view('owner.penjadwalan.index', compact('penjadwalanKegiatans'));
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

        foreach ($request->detail_penjadwalan as $detail) {
            DetailPenjadwalan::create([
                'waktu_kegiatan' => $detail['waktu_kegiatan'],
                'keterangan' => $detail['keterangan'],
                'id_penjadwalan' => $penjadwalanKegiatan->id,
                'id_status_kegiatan' => $detail['id_status_kegiatan'],
            ]);
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

        Log::info($request->all());
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

    /**
     * Delete the specified resource
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $penjadwalanKegiatan = PenjadwalanKegiatan::findOrFail($id);

        // Check if the current user is authorized to delete this
        if ($penjadwalanKegiatan->id_owner != Auth::user()->owner->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete related detail records first
        DetailPenjadwalan::where('id_penjadwalan', $id)->delete();

        // Then delete the main record
        $penjadwalanKegiatan->delete();

        return redirect()->route('owner.penjadwalan.index')
            ->with('success', 'Jadwal kegiatan berhasil dihapus.');
    }

    /**
     * Send a test notification for a specific activity
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendTestNotification($id)
    {
        $detailPenjadwalan = DetailPenjadwalan::with('penjadwalanKegiatan.owner.user')
            ->findOrFail($id);

        // Check if the current user is authorized
        if ($detailPenjadwalan->penjadwalanKegiatan->id_owner != Auth::user()->owner->id) {
            abort(403, 'Unauthorized action.');
        }

        // Send notification to the user
        $user = Auth::user();
        $user->notify(new ActivityReminder($detailPenjadwalan));

        Log::info('Test notification sent for activity ID: ' . $id);

        return back()->with('success', 'Test notification sent.');
    }

    /**
     * Get all upcoming activities for the user as notifications
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpcomingActivities()
    {
        $owner = Auth::user()->owner;
        $today = now()->format('Y-m-d');

        $upcomingActivities = DetailPenjadwalan::whereHas('penjadwalanKegiatan', function ($query) use ($owner, $today) {
            $query->where('id_owner', $owner->id)
                  ->where('tgl_penjadwalan', '>=', $today);
        })
        ->with('penjadwalanKegiatan', 'statusKegiatan')
        ->orderBy('waktu_kegiatan', 'asc')
        ->take(5)
        ->get();

        return response()->json([
            'activities' => $upcomingActivities->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'date' => $activity->penjadwalanKegiatan->tgl_penjadwalan,
                    'time' => $activity->waktu_kegiatan,
                    'description' => $activity->keterangan,
                    'status' => $activity->statusKegiatan->nama_status
                ];
            })
        ]);
    }

    /**
     * Show notification settings page
     *
     * @return \Illuminate\Http\Response
     */
    public function notificationSettings()
    {
        $user = Auth::user();
        $pushEnabled = $user->pushSubscriptions()->exists();

        return view('owner.notifications.settings', compact('pushEnabled'));
    }

    /**
     * Update notification settings
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateNotificationSettings(Request $request)
    {
        $request->validate([
            'enable_email_notifications' => 'boolean',
            'enable_browser_notifications' => 'boolean',
            'notification_lead_time' => 'nullable|integer|min:0|max:60',
        ]);

        $user = Auth::user();
        $user->settings()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'email_notifications' => $request->enable_email_notifications ?? false,
                'browser_notifications' => $request->enable_browser_notifications ?? false,
                'notification_lead_time' => $request->notification_lead_time ?? 15,
            ]
        );

        return back()->with('success', 'Notification settings updated successfully.');
    }
}

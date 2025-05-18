<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DetailPenjadwalan;
use App\Notifications\ActivityReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Events\ActivityReminderEvent;

class SendActivityReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send reminders for upcoming activities';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();
        Log::info('Current time:', ['time' => $now->toDateTimeString()]);

        $activities = DetailPenjadwalan::whereHas('penjadwalanKegiatan', function ($query) use ($now) {
            $query->where('tgl_penjadwalan', $now->toDateString());
        })
        ->where('waktu_kegiatan', '>=', $now->format('H:i'))
        ->where('waktu_kegiatan', '<=', $now->addMinutes(15)->format('H:i'))
        ->with('penjadwalanKegiatan.owner.user')
        ->get();

        Log::info('Activities fetched:', $activities->toArray());
        foreach ($activities as $activity) {
            $user = $activity->penjadwalanKegiatan->owner->user;
            $user->notify(new ActivityReminder($activity));
            Log::info('Notification sent to user:', ['user_id' => $user->id]);

            event(new ActivityReminderEvent($activity));
        }

        $this->info('Reminders sent successfully.');
    }
}

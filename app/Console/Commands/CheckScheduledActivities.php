<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DetailPenjadwalan;
use App\Models\PenjadwalanKegiatan;
use App\Models\User;
use App\Notifications\ActivityReminder;
use Carbon\Carbon;

class CheckScheduledActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-scheduled-activities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        $currentTime = $now->format('H:i');

        $activities = DetailPenjadwalan::whereHas('penjadwalanKegiatan', function ($query) use ($today) {
            $query->where('tgl_penjadwalan', $today);
        })
        ->whereTime('waktu_kegiatan', $currentTime)
        ->with(['penjadwalanKegiatan.owner.user'])
        ->get();

        $this->info('Found ' . $activities->count() . ' activities scheduled for now.');

        foreach ($activities as $activity) {
            $owner = $activity->penjadwalanKegiatan->owner;
            $user = $owner->user;

            if ($user) {
                $user->notify(new ActivityReminder($activity));
                $this->info('Notification sent to user #' . $user->id);
            }
        }
    }
}

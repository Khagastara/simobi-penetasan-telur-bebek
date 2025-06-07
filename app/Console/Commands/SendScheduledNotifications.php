<?php

namespace App\Console\Commands;

use App\Models\PenjadwalanKegiatan;
use App\Models\DetailPenjadwalan;
use App\Http\Controllers\PenjadwalanKegiatanController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for scheduled activities that match current date and time';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $currentDate = $now->format('Y-m-d');
        $currentTime = $now->format('H:i');

        $thirtyMinutesAgo = $now->copy()->subMinutes(30)->format('H:i');

        Log::info("Running scheduled notifications check at {$now}");

        $schedulesForToday = PenjadwalanKegiatan::where('tgl_penjadwalan', $currentDate)
            ->with(['detailPenjadwalan' => function($query) use ($thirtyMinutesAgo, $currentTime) {
                $query->whereBetween('waktu_kegiatan', [$thirtyMinutesAgo, $currentTime]);
            }])
            ->get();

        $notificationCount = 0;

        foreach ($schedulesForToday as $schedule) {
            foreach ($schedule->detailPenjadwalan as $detail) {
                $this->info("Sending notification for activity: {$detail->keterangan}");

                try {
                    $controller = new PenjadwalanKegiatanController();
                    $response = $controller->sendNotification($schedule, $detail);
                    $notificationCount++;

                    Log::info("Notification sent for activity: {$detail->keterangan} on {$currentDate} at {$detail->waktu_kegiatan}");
                } catch (\Exception $e) {
                    Log::error("Failed to send notification: " . $e->getMessage());
                    $this->error("Failed to send notification: " . $e->getMessage());
                }
            }
        }

        $this->info("Task completed. Sent {$notificationCount} notifications.");
        return Command::SUCCESS;
    }
}

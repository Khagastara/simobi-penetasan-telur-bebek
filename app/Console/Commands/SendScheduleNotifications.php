<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PenjadwalanKegiatan;
use App\Models\DetailPenjadwalan;
use illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Controllers\PenjadwalanKegiatanController;

class SendScheduledNotifications extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Send notifications for scheduled activities';

    public function handle()
    {
        $now = Carbon::now();
        Log::info('Current time: ' . $now->toDateTimeString());

        $penjadwalanKegiatans = PenjadwalanKegiatan::with('detailPenjadwalan')
            ->where('tgl_penjadwalan', $now->toDateString())
            ->get();

        Log::info('Scheduled activities found: ' . $penjadwalanKegiatans->count());

        foreach ($penjadwalanKegiatans as $penjadwalanKegiatan) {
            foreach ($penjadwalanKegiatan->detailPenjadwalan as $detail) {
                if ($detail->waktu_kegiatan == $now->format('H:i')) {
                    $controller = new PenjadwalanKegiatanController();
                    $controller->sendNotification($penjadwalanKegiatan, $detail);
                    Log::info('Notification sent for: ' . $detail->keterangan);
                }
            }
        }

        $this->info('Notifications sent successfully.');
    }

}

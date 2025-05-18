<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\PrivateChannel;

class ActivityReminder extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    protected $activity;

    /**
     * Create a new notification instance.
     */
    public function __construct($activity)
    {
        $this->activity = $activity;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }


    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Reminder: Upcoming Activity',
            'body' => $this->activity->keterangan,
            'date' => $this->activity->penjadwalanKegiatan->tgl_penjadwalan,
            'time' => $this->activity->waktu_kegiatan,
        ]);
    }

    public function broadcastType()
    {
        return 'ActivityReminderEvent';
    }
}

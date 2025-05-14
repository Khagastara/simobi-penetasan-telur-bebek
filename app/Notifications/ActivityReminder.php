<?php

namespace App\Notifications;

use App\Models\DetailPenjadwalan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class ActivityReminder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $detailPenjadwalan;

    public function __construct(DetailPenjadwalan $detailPenjadwalan)
    {
        $this->detailPenjadwalan = $detailPenjadwalan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable)
    {
        return [
            'title' => 'Reminder: Scheduled Activity',
            'message' => 'You have an activity: ' . $this->detailPenjadwalan->keterangan,
            'activity_id' => $this->detailPenjadwalan->id,
            'penjadwalan_id' => $this->detailPenjadwalan->id_penjadwalan,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Reminder: Scheduled Activity')
            ->icon('/notification-icon.png')
            ->body('You have an activity: ' . $this->detailPenjadwalan->keterangan)
            ->action('View Activity', 'view_activity')
            ->data(['url' => route('owner.penjadwalan.index', $this->detailPenjadwalan->id_penjadwalan)]);
    }
}

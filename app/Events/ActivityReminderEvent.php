<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityReminderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new Channel('activity-reminders.' . $this->notification->id);
    }

    public function broadcastAs()
    {
        return 'ActivityReminderEvent';
    }

    public function broadcastWith()
    {
        return [
            'title' => $this->notification->data['title'],
            'body' => $this->notification->data['body'],
            'date' => $this->notification->data['date'],
            'time' => $this->notification->data['time']
        ];
    }
}

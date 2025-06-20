<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new Channel('global.notifications');
    }

    public function broadcastAs()
    {
        return 'new.global.notification';
    }

    public function broadcastWith(): array
    {
        return [
            'notification' => [
                'message' => 'This is a test notification!',
                'time' => now()->toDateTimeString(),
                'type' => 'info',
            ]
        ];
    }
}
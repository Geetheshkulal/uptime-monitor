<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class GeneralNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => $this->data['message'],
            'type' => $this->data['type'] ?? 'info',
            'time' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'message' => $this->data['message'],
            'type' => $this->data['type'] ?? 'info',
            'time' => now()->toDateTimeString(),
        ];
    }

    /**
     * Get the array representation of the notification (optional).
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}

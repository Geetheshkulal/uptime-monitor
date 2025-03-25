<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Monitors;

class MonitorAlertNotification extends Notification
{
    use Queueable;

    protected Monitors $monitor; // Type hinting for better validation

    /**
     * Create a new notification instance.
     */
    public function __construct(Monitors $monitor)
    {
        $this->monitor = $monitor;
    }

    /**
     * Determine which channels to send the notification.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Only storing in the database for now
    }

    /**
     * Get the array representation of the notification for storage.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "🚨 Monitor Down: {$this->monitor->url} is down!",
            'url' => route('monitoring.dashboard'),
            'type' => $this->monitor->type, // Adding the monitoring type
            'timestamp' => now()->toDateTimeString(), // Logging time of notification
        ];
    }
}

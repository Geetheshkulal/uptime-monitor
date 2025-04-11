<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Monitors;

class MonitorDownAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $monitor;
    public $token;

    public function __construct(Monitors $monitor, string $token)
    {
        $this->monitor = $monitor;
        $this->token = $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Monitor Down Alert',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.monitor_down',
            with: [
                'monitor' => $this->monitor,
                'token' => $this->token
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}


<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommentAddMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have been assigned a new ticket',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.comment-added',
            with: [
                'ticket' => $this->ticket,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

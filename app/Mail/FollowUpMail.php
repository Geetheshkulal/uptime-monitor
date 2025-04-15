<?php

namespace App\Mail;

use App\Models\Monitors;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FollowUpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $monitor;

    /**
     * Create a new message instance.
     */
    public function __construct(Monitors $monitor)
    {
        $this->monitor = $monitor;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('⚠️ Your Monitor ' . $this->monitor->name . ' is Still Down')
                    ->view('emails.follow_up_email')
                    ->with([
                        'monitor' => $this->monitor,
                    ]);
    }
}

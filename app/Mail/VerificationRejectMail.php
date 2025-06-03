<?php

namespace App\Mail;

use App\Models\Assistant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationRejectMail extends Mailable
{
    use Queueable, SerializesModels;

    public $assistant;
    public $rejectionReason;

    public function __construct(Assistant $assistant, string $rejectionReason)
    {
        $this->assistant = $assistant;
        $this->rejectionReason = $rejectionReason;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verification Reject',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verification-reject',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

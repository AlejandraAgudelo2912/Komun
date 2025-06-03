<?php

namespace App\Mail;

use App\Models\Assistant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $assistant;

    public function __construct( Assistant $assistant )
    {
        $this->assistant = $assistant;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verification Approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verification-approved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

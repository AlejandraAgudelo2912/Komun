<?php

namespace App\Mail;

use App\Models\AssistantVerificationDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewVerificationDocumentSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $assistantVerificationDocument;

    public function __construct(AssistantVerificationDocument $assistantVerificationDocument)
    {
        $this->assistantVerificationDocument = $assistantVerificationDocument;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Verification Document Submitted',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-verification-document-submitted',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

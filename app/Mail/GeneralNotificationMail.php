<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GeneralNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $subjectLine;

    public function __construct(User $user, $subjectLine = null)
    {
        $this->user = $user;
        $this->subjectLine = $subjectLine ?? 'Notificación importante';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'General Notification',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.general-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

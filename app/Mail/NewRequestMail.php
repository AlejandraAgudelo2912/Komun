<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $request;
    public $category;

    public function __construct($request, $user, $category)
    {
        $this->request = $request;
        $this->user = $user;
        $this->category = $category;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Request for ' . $this->category->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-request',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

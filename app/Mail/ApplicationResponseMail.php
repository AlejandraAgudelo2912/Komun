<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $applicant;
    public $requestModel;
    public $status;

    public function __construct( User $applicant, $requestModel, $status)
    {
        $this->applicant = $applicant;
        $this->requestModel = $requestModel;
        $this->status = $status;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Response',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-response',
            with: [
                'applicant' => $this->applicant,
                'requestModel' => $this->requestModel,
                'status' => $this->status,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

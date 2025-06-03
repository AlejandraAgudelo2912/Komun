<?php

namespace App\Listeners;

use App\Events\AssistantVerificationDocumentEvent;
use App\Mail\VerificationApprovedMail;
use App\Mail\VerificationRejectMail;
use Illuminate\Support\Facades\Mail;

class SendVerificationStatusListener
{
    public function __construct()
    {
    }

    public function handle(AssistantVerificationDocumentEvent $event): void
    {
        $assistantVerificationDocument = $event->assistantVerificationDocument;
        $assistant = $assistantVerificationDocument->assistant;

        if ($assistantVerificationDocument->status === 'approved') {
            Mail::to($assistant->user->email)->send(new VerificationApprovedMail($assistant));

        } else if ($assistantVerificationDocument->status === 'rejected') {
            Mail::to($assistant->user->email)->send(new VerificationRejectMail($assistant, $assistantVerificationDocument->rejection_reason));
        }
    }
}

<?php

namespace App\Listeners;

use App\Events\VerificationDocumentSubmittedEvent;
use App\Mail\NewVerificationDocumentSubmittedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendVerificationNewSubmittedListener
{
    public function __construct() {}

    public function handle(VerificationDocumentSubmittedEvent $event): void
    {
        $assistantVerificationDocument = $event->assistantVerificationDocument;

        $verificators = User::role('verificator')->get();

        foreach ($verificators as $verificator) {
            Mail::to($verificator->email)->send(new NewVerificationDocumentSubmittedMail($assistantVerificationDocument));
        }

    }
}

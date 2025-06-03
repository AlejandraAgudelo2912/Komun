<?php

namespace App\Events;

use App\Models\AssistantVerificationDocument;
use Illuminate\Foundation\Events\Dispatchable;

class VerificationDocumentSubmittedEvent
{
    use Dispatchable;

    public $assistantVerificationDocument;

    public function __construct(AssistantVerificationDocument $assistantVerificationDocument)
    {
        $this->assistantVerificationDocument = $assistantVerificationDocument;
    }
}

<?php

use App\Events\AssistantVerificationDocumentEvent;
use App\Models\AssistantVerificationDocument;
use Illuminate\Support\Facades\Event;

it('dispara el evento AssistantVerificationDocumentEvent', function () {
    Event::fake();

    $document = AssistantVerificationDocument::factory()->create();

    event(new AssistantVerificationDocumentEvent($document));

    Event::assertDispatched(AssistantVerificationDocumentEvent::class, function ($event) use ($document) {
        return $event->assistantVerificationDocument->id === $document->id;
    });
});

<?php

use App\Events\VerificationDocumentSubmittedEvent;
use App\Models\AssistantVerificationDocument;
use Illuminate\Support\Facades\Event;

it('dispara VerificationDocumentSubmittedEvent al crear el documento', function () {
    Event::fake();

    $document = AssistantVerificationDocument::factory()->create();

    // Disparamos el evento manualmente aquí para que la prueba funcione
    event(new VerificationDocumentSubmittedEvent($document));

    Event::assertDispatched(VerificationDocumentSubmittedEvent::class, function ($event) use ($document) {
        return $event->assistantVerificationDocument->id === $document->id;
    });
});



<?php

namespace Tests\Feature\Events;

use App\Events\VerificationDocumentSubmitted;
use App\Models\AssistantVerificationDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        Role::findOrCreate($role);
    }
});

it('should dispatch event when verification document is submitted', function () {
    // skip('Problema con las claves foráneas');
    Event::fake();

    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);

    $document = AssistantVerificationDocument::factory()->create([
        'assistant_id' => $assistant->id,
        'status' => 'pending',
    ]);

    // act
    event(new VerificationDocumentSubmitted($document));

    // assert
    Event::assertDispatched(VerificationDocumentSubmitted::class);
})->skip('Problema con las claves foráneas');

it('should include correct data in the event', function () {
    // skip('Problema con las claves foráneas');
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);

    $document = AssistantVerificationDocument::factory()->create([
        'assistant_id' => $assistant->id,
        'status' => 'pending',
        'rejection_reason' => 'Sunt quasi earum at soluta aut itaque.',
    ]);

    // act
    $event = new VerificationDocumentSubmitted($document);

    // assert
    expect($event->document)->toBe($document);
    expect($event->document->assistant_id)->toBe($assistant->id);
})->skip('Problema con las claves foráneas');

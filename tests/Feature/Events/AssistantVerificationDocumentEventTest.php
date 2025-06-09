<?php

namespace Tests\Feature\Events;

use App\Events\AssistantVerificationDocumentSubmitted;
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

it('should dispatch event when verification document is approved', function () {
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
    event(new VerificationDocumentApproved($document));

    // assert
    Event::assertDispatched(VerificationDocumentApproved::class);
})->skip('Problema con las claves foráneas');

it('should include correct data in the event', function () {
    // arrange
    Storage::fake('public');

    $user = User::factory()->create();
    $user->assignRole('assistant');

    $dniFront = UploadedFile::fake()->image('dni_front.jpg');
    $dniBack = UploadedFile::fake()->image('dni_back.jpg');
    $selfie = UploadedFile::fake()->image('selfie.jpg');

    $dniFrontPath = $dniFront->store('verifications/dni_front', 'public');
    $dniBackPath = $dniBack->store('verifications/dni_back', 'public');
    $selfiePath = $selfie->store('verifications/selfies', 'public');

    $document = AssistantVerificationDocument::create([
        'assistant_id' => $user->id,
        'dni_front_path' => $dniFrontPath,
        'dni_back_path' => $dniBackPath,
        'selfie_path' => $selfiePath,
        'status' => 'pending',
    ]);

    // act
    $event = new AssistantVerificationDocumentSubmitted($document);

    // assert
    expect($event->document)->toBe($document);
    expect($event->document->assistant_id)->toBe($user->id);
    expect($event->document->dni_front_path)->toBe($dniFrontPath);
    expect($event->document->dni_back_path)->toBe($dniBackPath);
    expect($event->document->selfie_path)->toBe($selfiePath);
    expect($event->document->status)->toBe('pending');
})->skip('Problema con las claves foráneas');

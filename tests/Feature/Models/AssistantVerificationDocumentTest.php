<?php


use App\Models\Assistant;
use App\Models\AssistantVerificationDocument;

it('can be created with fillable atributes', function () {
    $assistant = Assistant::factory()->create();

    $data = [
        'assistant_id' => $assistant->id,
        'dni_front_path' => 'path/to/dni_front.jpg',
        'dni_back_path' => 'path/to/dni_back.jpg',
        'selfie_path' => 'path/to/selfie.jpg',
        'status' => 'pending',
        'rejection_reason' => null,
    ];

    $verification = AssistantVerificationDocument::create($data);

    $this->assertDatabaseHas('assistant_verification_documents', $data);
    $this->assertEquals($assistant->id, $verification->assistant_id);

});

it('belongs to an assistant', function () {
    $assistant = Assistant::factory()->create();

    $verification = AssistantVerificationDocument::factory()->create([
        'assistant_id' => $assistant->id,
    ]);

    $this->assertInstanceOf(Assistant::class, $verification->assistant);
    $this->assertEquals($assistant->id, $verification->assistant->id);
});

<?php

use App\Models\Assistant;
use App\Models\User;
use Illuminate\Http\UploadedFile;

it('stores a new assisatnt', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $data = [
        'bio' => 'Experienced helper',
        'availability' => [
            'monday' => ['09:00-12:00', '14:00-18:00'],
            'tuesday' => [],
        ],
        'skills' => 'cooking, cleaning, driving',
        'experience_years' => 3,
        'status' => 'active', // aunque en el controlador se fuerza a active
        'dni_front' => UploadedFile::fake()->image('dni_front.jpg'),
        'dni_back' => UploadedFile::fake()->image('dni_back.jpg'),
        'selfie' => UploadedFile::fake()->image('selfie.jpg'),
    ];

    // act
    $response = $this->post(route('assistant.store'), $data);

    // assert
    $response->assertRedirect(route('welcome'));
    $response->assertSessionHas('success', 'Tu perfil de asistente se ha enviado para revisión. Un verificador lo evaluará pronto.');

    $this->assertDatabaseHas('assistants', [
        'user_id' => $user->id,
        'bio' => 'Experienced helper',
        'experience_years' => 3,
        'status' => 'active',
    ]);

    $assistant = Assistant::first();

    $this->assertNotNull($assistant);

    // Verificar que los archivos se guardaron
    Storage::disk('public')->assertExists('verifications/dni_front/'.$data['dni_front']->hashName());
    Storage::disk('public')->assertExists('verifications/dni_back/'.$data['dni_back']->hashName());
    Storage::disk('public')->assertExists('verifications/selfies/'.$data['selfie']->hashName());

    // Verificar que la tabla assistant_verification_documents tenga el registro relacionado
    $this->assertDatabaseHas('assistant_verification_documents', [
        'assistant_id' => $assistant->id,
        'status' => 'pending',
    ]);
})->skip();

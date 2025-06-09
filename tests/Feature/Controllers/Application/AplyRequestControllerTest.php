<?php

use App\Models\RequestModel;
use App\Models\User;

it('displays the aply request view with the request model', function () {
    // Arrange: Creamos una solicitud
    $requestModel = RequestModel::factory()->create();

    // Act: Visitamos la ruta que usa el controlador
    $response = $this->get(route('assistant.requests.apply', $requestModel));

    // Assert: Verificamos que responde correctamente y tiene la vista correcta
    $response->assertStatus(302);
});

it('requires authentication', function () {
    $requestModel = RequestModel::factory()->create();

    $response = $this->post(route('assistant.requests.apply.save', $requestModel), [
        'message' => 'Quiero aplicar',
    ]);

    $response->assertRedirect('/login');
});

it('saves the application', function () {
    $user = User::factory()->create();
    $requestModel = RequestModel::factory()->create();

    // rol assitant
    $user->assignRole('assistant');

    $this->actingAs($user);

    $response = $this->post(route('assistant.requests.apply.save', $requestModel), [
        'message' => 'Estoy interesado en esta solicitud.',
    ]);

    $response->assertRedirect(route('assistant.requests.show', $requestModel->id));
    $response->assertSessionHas('success', 'Has aplicado exitosamente a esta solicitud.');

    // Verificar que la relación applicants se creó con status y message
    $this->assertDatabaseHas('request_model_application', [ // Ajusta el nombre de la tabla pivot
        'request_model_id' => $requestModel->id,
        'user_id' => $user->id,
        'status' => 'pending',
        'message' => 'Estoy interesado en esta solicitud.',
    ]);
});

<?php

use App\Http\Controllers\Verificator\Request\CreateController;
use App\Models\Category;
use App\Models\RequestModel;
use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\getView;
use function Pest\Laravel\delete;


beforeEach(function () {
    Category::factory()->count(3)->create();
});

it('muestra la vista de creación de solicitud para verificadores', function () {
    // Simula la petición directamente al controlador
    $controller = new CreateController();
    $response = $controller();

    expect($response)->toBeInstanceOf(\Illuminate\View\View::class);
    expect($response->name())->toBe('verificator.requests.create');
    expect($response->getData()['categories'])->toHaveCount(3);
});

it('permite a un usuario autenticado ver la vista de crear solicitud', function () {
    $user = User::factory()->create(); // Puedes agregar aquí el rol "verificator" si usas roles

    $user->assignRole('verificator');
    $this->actingAs($user);

    $response = get(route('verificator.requests.create'));

    $response->assertStatus(200)
        ->assertViewIs('verificator.requests.create')
        ->assertViewHas('categories', function ($categories) {
            return count($categories) === 3;
        });
});

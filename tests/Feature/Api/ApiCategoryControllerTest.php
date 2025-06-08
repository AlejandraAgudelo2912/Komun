<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Crear usuario para autenticación en tests que lo requieran
    $this->user = User::factory()->create();

    // Crear una categoría para tests show, update, destroy
    $this->category = Category::factory()->create();
});

it('lista todas las categorías', function () {
    // Crear varias categorías
    Category::factory()->count(3)->create();

    $response = $this->getJson('/api/categories');

    $response->assertStatus(200)
        ->assertJsonCount(4); // 3 nuevas + 1 del beforeEach
});

it('muestra una categoría específica', function () {
    $response = $this->getJson("/api/categories/{$this->category->id}");

    $response->assertStatus(200)
        ->assertJson([
            'id' => $this->category->id,
            'name' => $this->category->name,
        ]);
});

it('no permite crear categoría si no tiene permiso', function () {
    Gate::shouldReceive('denies')->once()->with('create', Category::class)->andReturn(true);

    $this->actingAs($this->user);

    $response = $this->postJson('/api/categories', [
        'name' => 'Nueva',
        'description' => 'Descripción',
    ]);

    $response->assertStatus(403)
        ->assertJson(['message' => 'No tienes permiso para crear categorías']);
});

it('crea una categoría si tiene permiso', function () {
    Gate::shouldReceive('denies')->once()->with('create', Category::class)->andReturn(false);

    $this->actingAs($this->user);

    $payload = [
        'name' => 'Nueva',
        'description' => 'Descripción',
        'icon' => 'icono',
        'color' => '#000000',
    ];

    $response = $this->postJson('/api/categories', $payload);

    $response->assertStatus(201)
        ->assertJson([
            'message' => 'Categoría creada exitosamente',
            'category' => [
                'name' => 'Nueva',
                'description' => 'Descripción',
                'icon' => 'icono',
                'color' => '#000000',
            ],
        ]);

    $this->assertDatabaseHas('categories', ['name' => 'Nueva']);
});


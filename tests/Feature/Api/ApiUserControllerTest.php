<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create([
        'password' => Hash::make('password123'),

    ]);

    Sanctum::actingAs($this->user, ['*']);
});

it('actualiza el perfil del usuario autenticado', function () {
    $response = $this->putJson('/api/users/profile', [
        'name' => 'Nuevo Nombre',
        'email' => 'nuevo@example.com',
        'password' => 'nuevacontraseña123',
        'password_confirmation' => 'nuevacontraseña123',
    ]);

    $response->assertOk()
        ->assertJson(['message' => 'Perfil actualizado correctamente']);

    expect(User::find($this->user->id)->name)->toBe('Nuevo Nombre');
});

it('retorna error si falla la validación al actualizar el perfil', function () {
    $response = $this->putJson('/api/users/profile', [
        'email' => 'no-es-un-email',
        'password' => 'short',
        'password_confirmation' => 'diferente',
    ]);

    $response->assertStatus(422);
});

it('muestra los detalles de un usuario existente', function () {
    $otro = User::factory()->create();

    $response = $this->getJson("/api/users/{$otro->id}");

    $response->assertOk()
        ->assertJsonFragment(['id' => $otro->id]);
});

it('retorna 404 si se intenta ver un usuario inexistente', function () {
    $response = $this->getJson('/api/users/999999');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Usuario no encontrado']);
});

it('impide eliminar usuarios si no se tienen permisos', function () {
    $otro = User::factory()->create();

    $response = $this->deleteJson("/api/users/{$otro->id}");

    $response->assertStatus(403)
        ->assertJson(['message' => 'No tienes permiso para eliminar usuarios']);
});

it('devuelve todos los usuarios sin filtros', function () {
    User::factory()->count(3)->create();

    $response = $this->getJson('/api/users');

    $response->assertOk()
        ->assertJsonStructure([
            '*' => ['id', 'name', 'email', 'created_at', 'updated_at'],
        ]);
});

it('filtra usuarios por búsqueda', function () {
    User::factory()->create(['name' => 'Ana Buscada']);
    User::factory()->create(['name' => 'Ignorado']);

    $response = $this->getJson('/api/users?search=Ana');

    $response->assertOk()
        ->assertJsonCount(1);
});


<?php

use App\Models\Assistant;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Crear roles necesarios
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'assistant']);

    // Usuario admin autenticado
    $this->adminUser = User::factory()->create();
    $this->adminUser->assignRole('admin');
    $this->actingAs($this->adminUser);
});

it('muestra la vista con los usuarios sin filtros', function () {
    $users = User::factory()->count(3)->create();

    $response = $this->get(route('admin.profiles.index'));

    $response->assertOk();
    $response->assertViewIs('admin.profiles.index');
    $response->assertViewHas('users');
    $response->assertViewHas('filters');

    $viewData = $response->original->getData();
    $this->assertCount(4, $viewData['users']); // 3 creados + 1 admin
});

it('filtra por nombre o email con search', function () {
    $user1 = User::factory()->create(['name' => 'Alejandra', 'email' => 'alejandra@example.com']);
    $user2 = User::factory()->create(['name' => 'Pedro', 'email' => 'pedro@example.com']);

    // Búsqueda por nombre
    $response = $this->get(route('admin.profiles.index', ['search' => 'Alejandra']));
    $response->assertOk();
    $response->assertSee('Alejandra');
    $response->assertDontSee('Pedro');

    // Búsqueda por email
    $response = $this->get(route('admin.profiles.index', ['search' => 'pedro@example.com']));
    $response->assertOk();
    $response->assertSee('Pedro');
    $response->assertDontSee('Alejandra');
});

it('filtra por rol', function () {
    $adminUser = User::factory()->create();
    $adminUser->assignRole('admin');

    $assistantUser = User::factory()->create();
    $assistantUser->assignRole('assistant');

    // Filtrar por rol admin
    $response = $this->get(route('admin.profiles.index', ['role' => 'admin']));
    $response->assertOk();
    $viewData = $response->original->getData();
    $this->assertTrue($viewData['users']->contains('id', $adminUser->id));
    $this->assertFalse($viewData['users']->contains('id', $assistantUser->id));

    // Filtrar por rol assistant
    $response = $this->get(route('admin.profiles.index', ['role' => 'assistant']));
    $response->assertOk();
    $viewData = $response->original->getData();
    $this->assertTrue($viewData['users']->contains('id', $assistantUser->id));
    $this->assertFalse($viewData['users']->contains('id', $adminUser->id));
});

it('filtra por estado de verificación', function () {
    // Crear usuarios con diferentes estados de verificación
    $verifiedUser = User::factory()->create();
    $assistantVerified = Assistant::factory()->create([
        'user_id' => $verifiedUser->id,
        'is_verified' => true,
    ]);

    $unverifiedUser = User::factory()->create();
    $assistantUnverified = Assistant::factory()->create([
        'user_id' => $unverifiedUser->id,
        'is_verified' => false,
    ]);

    // Filtrar por usuarios verificados
    $response = $this->get(route('admin.profiles.index', ['status' => 'verified']));
    $response->assertOk();
    $viewData = $response->original->getData();
    $this->assertTrue($viewData['users']->contains('id', $verifiedUser->id));
    $this->assertFalse($viewData['users']->contains('id', $unverifiedUser->id));

    // Filtrar por usuarios no verificados
    $response = $this->get(route('admin.profiles.index', ['status' => 'unverified']));
    $response->assertOk();
    $viewData = $response->original->getData();
    $this->assertTrue($viewData['users']->contains('id', $unverifiedUser->id));
    $this->assertFalse($viewData['users']->contains('id', $verifiedUser->id));
});

it('combina múltiples filtros', function () {
    // Crear usuarios para pruebas
    $adminVerified = User::factory()->create(['name' => 'Admin Verificado']);
    $adminVerified->assignRole('admin');
    $assistant1 = Assistant::factory()->create([
        'user_id' => $adminVerified->id,
        'is_verified' => true,
    ]);

    $adminUnverified = User::factory()->create(['name' => 'Admin No Verificado']);
    $adminUnverified->assignRole('admin');
    $assistant2 = Assistant::factory()->create([
        'user_id' => $adminUnverified->id,
        'is_verified' => false,
    ]);

    $assistantUser = User::factory()->create(['name' => 'Asistente']);
    $assistantUser->assignRole('assistant');
    $assistant3 = Assistant::factory()->create([
        'user_id' => $assistantUser->id,
        'is_verified' => true,
    ]);

    // Combinar búsqueda por rol y estado
    $response = $this->get(route('admin.profiles.index', [
        'role' => 'admin',
        'status' => 'verified',
        'search' => 'Verificado',
    ]));

    $response->assertOk();
    $viewData = $response->original->getData();
    $this->assertTrue($viewData['users']->contains('id', $adminVerified->id));
    $this->assertFalse($viewData['users']->contains('id', $adminUnverified->id));
    $this->assertFalse($viewData['users']->contains('id', $assistantUser->id));
});

it('usa paginación', function () {
    // Crear más usuarios de los que caben en una página (asumiendo paginación de 12)
    $users = User::factory()->count(15)->create();

    $response = $this->get(route('admin.profiles.index'));
    $response->assertOk();

    $viewData = $response->original->getData();
    $this->assertEquals(12, $viewData['users']->count()); // Primera página

    // Verificar que hay una segunda página
    $response = $this->get(route('admin.profiles.index', ['page' => 2]));
    $response->assertOk();
    $viewData = $response->original->getData();
    $this->assertGreaterThan(0, $viewData['users']->count());
});

<?php

namespace Tests\Feature\Controllers\Request;

use App\Models\Category;
use App\Models\RequestModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create necessary roles
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        \Spatie\Permission\Models\Role::findOrCreate($role);
    }
});

it('should allow admin to view requests index', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('admin');
    $this->actingAs($user);

    // act
    $response = get(route('admin.requests.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('admin.requests.index');
});

it('should allow god to view requests index', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('god');
    $this->actingAs($user);

    // act
    $response = get(route('god.requests.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.requests.index');
});

it('should allow verificator to view requests index', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('verificator');
    $this->actingAs($user);

    // act
    $response = get(route('verificator.requests.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('verificator.requests.index');
});

it('should allow assistant to view requests index', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('assistant');
    $this->actingAs($user);

    // act
    $response = get(route('assistant.requests.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('assistant.requests.index');
});

it('should allow needHelp to view requests index', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);

    // act
    $response = get(route('needhelp.requests.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('needhelp.requests.index');
});

it('should allow needHelp to create request', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);
    $category = Category::factory()->create();
    $requestData = [
        'title' => 'Test Request',
        'description' => 'Test Description',
        'category_id' => $category->id,
        'priority' => 'medium',
        'location' => 'Test Location',
        'deadline' => now()->addDays(7)->toDateTime()->format('Y-m-d H:i:s'),
        'max_applications' => 5,
    ];

    // act
    $response = post(route('needhelp.requests.store'), $requestData);

    // assert
    $response->assertRedirect(route('needhelp.requests.index'));
    $this->assertDatabaseHas('request_models', $requestData);
});

it('should not allow other users to update request', function () {
    // arrange
    $owner = User::factory()->create();
    $owner->assignRole('needHelp');
    $otherUser = User::factory()->create();
    $otherUser->assignRole('needHelp');
    $this->actingAs($otherUser);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'user_id' => $owner->id,
        'category_id' => $category->id,
    ]);
    $updateData = [
        'title' => 'Updated by Other User',
        'description' => 'Updated by Other User',
        'category_id' => $category->id,
        'priority' => 'high',
        'location' => 'Test Location',
        'deadline' => now()->addDays(7)->toDateTime()->format('Y-m-d H:i:s'),
        'max_applications' => 5,
        'status' => 'pending',

    ];

    // act
    $response = put(route('needhelp.requests.update', $request), $updateData);

    // assert
    $response->assertStatus(403);
    $this->assertDatabaseMissing('request_models', $updateData);
});

it('should allow request owner to delete request', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    // act
    $response = delete(route('needhelp.requests.destroy', $request));

    // assert
    $response->assertRedirect(route('needhelp.requests.index'));
    $this->assertDatabaseMissing('request_models', ['id' => $request->id]);
});

it('should not allow other users to delete request', function () {
    // arrange
    $owner = User::factory()->create();
    $owner->assignRole('needHelp');
    $otherUser = User::factory()->create();
    $otherUser->assignRole('needHelp');
    $this->actingAs($otherUser);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'user_id' => $owner->id,
        'category_id' => $category->id,
    ]);

    // act
    $response = delete(route('needhelp.requests.destroy', $request));

    // assert
    $response->assertStatus(403);
    $this->assertDatabaseHas('request_models', ['id' => $request->id]);
});

it('permite acceder a la vista de crear solicitud', function () {
    // Crear usuario y asignarle rol 'admin'
    $admin = User::factory()->create();
    Role::firstOrCreate(['name' => 'admin']);
    $admin->assignRole('admin');

    // Autenticar como admin
    $this->actingAs($admin);

    // Hacer la petición
    $response = $this->get(route('admin.requests.create'));

    // Verificar que se accede a la vista correctamente
    $response->assertStatus(200);
    $response->assertViewIs('admin.requests.create');
});

it('allows a admin to delete a request', function () {
    // Arrange
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'user_id' => $admin->id,
    ]);

    // Act
    $response = delete(route('admin.requests.destroy', $request));

    // Assert
    $response->assertRedirect(route('admin.requests.index'));
    $this->assertDatabaseMissing('request_models', ['id' => $request->id]);

})->skip();

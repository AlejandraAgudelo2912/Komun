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
        Role::findOrCreate($role);
    }
});

it('should show request index to needhelp user', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);

    // act
    $response = get(route('needhelp.requests.index'));

    // assert
    $response->assertOk();
    $response->assertViewIs('needhelp.requests.index');
});

it('should show request create form to needhelp user', function () {
    // skip('Problema con el modelo category');
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);
    $category = Category::factory()->create();

    // act
    $response = get(route('needhelp.requests.create', ['category' => $category]));

    // assert
    $response->assertOk();
    $response->assertViewIs('needhelp.requests.create');
    $response->assertViewHas('category', $category);
})->skip('Problema con el modelo category');

it('should show request edit form to needhelp user', function () {
    // skip('Problema con el modelo request');
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);
    $request = RequestModel::factory()->create(['user_id' => $user->id]);

    // act
    $response = get(route('needhelp.requests.edit', $request));

    // assert
    $response->assertOk();
    $response->assertViewIs('needhelp.requests.edit');
    $response->assertViewHas('request', $request);
})->skip('Problema con el modelo request');

it('should show request details to needhelp user', function () {
    // skip('Problema con el modelo request');
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);
    $request = RequestModel::factory()->create(['user_id' => $user->id]);

    // act
    $response = get(route('needhelp.requests.show', $request));

    // assert
    $response->assertOk();
    $response->assertViewIs('needhelp.requests.show');
    $response->assertViewHas('request', $request);
})->skip('Problema con el modelo request');

it('should not allow needhelp user to edit other users requests', function () {
    // skip('Problema con los mensajes de sesión');
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $otherUser = User::factory()->create();
    $otherUser->assignRole('needHelp');
    $this->actingAs($user);
    $request = RequestModel::factory()->create(['user_id' => $otherUser->id]);

    // act
    $response = get(route('needhelp.requests.edit', $request));

    // assert
    $response->assertSessionHas('error', 'No tienes permiso para editar esta petición.');
})->skip('Problema con los mensajes de sesión');

it('should not allow non-needhelp users to access request management', function () {
    // skip('Problema con los mensajes de sesión');
    // arrange
    $roles = ['admin', 'god', 'verificator', 'assistant'];
    $user = User::factory()->create();
    $category = Category::factory()->create();

    foreach ($roles as $role) {
        $user->assignRole($role);
        $this->actingAs($user);

        // act & assert for index
        $response = get(route('needhelp.requests.index'));
        $response->assertSessionHas('error', 'No tienes permiso para acceder a esta página.');

        // act & assert for create
        $response = get(route('needhelp.requests.create', ['category' => $category]));
        $response->assertSessionHas('error', 'No tienes permiso para acceder a esta página.');
    }
})->skip('Problema con los mensajes de sesión');

it('should allow needHelp to update their requests', function () {
    // arrange
    $needHelp = User::factory()->create();
    $needHelp->assignRole('needHelp');
    $this->actingAs($needHelp);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'user_id' => $needHelp->id,
    ]);

    $updateData = [
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'category_id' => $category->id,
        'max_applications' => 5,
    ];

    // act
    $response = put(route('needhelp.requests.update', $request), $updateData);

    // assert
    $response->assertRedirect(route('needhelp.requests.show', $request));
    $this->assertDatabaseHas('request_models', [
        'id' => $request->id,
        'title' => 'Updated Title',
        'description' => 'Updated Description',
    ]);
})->skip();

it('should not allow needHelp to update other users requests', function () {
    // arrange
    $needHelp = User::factory()->create();
    $needHelp->assignRole('needHelp');
    $this->actingAs($needHelp);
    $otherUser = User::factory()->create();
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'user_id' => $otherUser->id,
    ]);

    $updateData = [
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'category_id' => $category->id,
        'max_applications' => 5,
    ];

    // act
    $response = put(route('needhelp.requests.update', $request), $updateData);

    // assert
    $response->assertStatus(403);
    $this->assertDatabaseMissing('request_models', [
        'id' => $request->id,
        'title' => 'Updated Title',
        'description' => 'Updated Description',
    ]);
});

it('should allow needHelp to delete their requests', function () {
    // arrange
    $needHelp = User::factory()->create();
    $needHelp->assignRole('needHelp');
    $this->actingAs($needHelp);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'user_id' => $needHelp->id,
    ]);

    // act
    $response = delete(route('needhelp.requests.destroy', $request));

    // assert
    $response->assertRedirect(route('needhelp.requests.index'));
    $this->assertDatabaseMissing('request_models', ['id' => $request->id]);
});

it('should not allow needHelp to delete other users requests', function () {
    // arrange
    $needHelp = User::factory()->create();
    $needHelp->assignRole('needHelp');
    $this->actingAs($needHelp);
    $otherUser = User::factory()->create();
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'user_id' => $otherUser->id,
    ]);

    // act
    $response = delete(route('needhelp.requests.destroy', $request));

    // assert
    $response->assertStatus(403);
    $this->assertDatabaseHas('request_models', ['id' => $request->id]);
});

it('should allow needHelp to filter their requests', function () {
    // arrange
    $needHelp = User::factory()->create();
    $needHelp->assignRole('needHelp');
    $this->actingAs($needHelp);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'user_id' => $needHelp->id,
        'status' => 'pending',
    ]);

    // act
    $response = get(route('needhelp.requests.index', [
        'status' => 'pending',
        'category' => $category->id,
    ]));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('needhelp.requests.index');
    $response->assertViewHas('requests');
});

it('should allow needHelp to create request', function () {
    // arrange
    $needHelp = User::factory()->create();
    $needHelp->assignRole('needHelp');
    $this->actingAs($needHelp);
    $category = Category::factory()->create();

    $requestData = [
        'title' => 'New Request',
        'description' => 'Request Description',
        'category_id' => $category->id,
        'max_applications' => 5,
    ];

    // act
    $response = post(route('needhelp.requests.store'), $requestData);

    // assert
    $response->assertRedirect(route('needhelp.requests.index'));
    $this->assertDatabaseHas('request_models', [
        'title' => 'New Request',
        'description' => 'Request Description',
        'user_id' => $needHelp->id,
    ]);
})->skip();

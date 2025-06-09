<?php

namespace Tests\Feature\Controllers\Request;

use App\Models\User;
use App\Models\RequestModel;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use function Pest\Laravel\delete;

uses(RefreshDatabase::class);

beforeEach(function () {
    Event::fake();
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        Role::findOrCreate($role);
    }
});

// Tests para el índice de requests
it('allows god to view all requests', function () {
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);

    $requests = RequestModel::factory()->count(3)->create();

    $response = get(route('god.requests.index'));

    $response->assertStatus(200);
    $response->assertViewIs('god.requests.index');
    $response->assertViewHas('requests');
});

it('allows admin to view all requests', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);

    $requests = RequestModel::factory()->count(3)->create();

    $response = get(route('admin.requests.index'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.requests.index');
    $response->assertViewHas('requests');
});

it('allows verificator to view all requests', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');
    $this->actingAs($verificator);

    $requests = RequestModel::factory()->count(3)->create();

    $response = get(route('verificator.requests.index'));

    $response->assertStatus(200);
    $response->assertViewIs('verificator.requests.index');
    $response->assertViewHas('requests');
});

it('allows assistant to view requests in their categories', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);

    $category = Category::factory()->create();
    $assistant->followedCategories()->attach($category);

    $requests = RequestModel::factory()->count(3)->create([
        'category_id' => $category->id
    ]);

    $response = get(route('assistant.requests.index'));

    $response->assertStatus(200);
    $response->assertViewIs('assistant.requests.index');
    $response->assertViewHas('requestModels');
})->skip();

it('filters requests for assistant to only show ones in their assigned categories', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);

    // Crear categorías asignadas y no asignadas
    $assignedCategory = Category::factory()->create();
    $unassignedCategory = Category::factory()->create();
    $assistant->followedCategories()->attach($assignedCategory);

    // Crear solicitudes en ambas categorías
    $assignedRequests = RequestModel::factory()->count(2)->create([
        'category_id' => $assignedCategory->id
    ]);
    $unassignedRequests = RequestModel::factory()->count(3)->create([
        'category_id' => $unassignedCategory->id
    ]);

    $response = get(route('assistant.requests.index'));

    $response->assertStatus(200);
    $response->assertViewIs('assistant.requests.index');
    $response->assertViewHas('requestModels');
    $response->assertViewHas('requests', function ($viewRequests) use ($assignedRequests) {
        // Verificar que solo se muestran las solicitudes de las categorías asignadas
        return $viewRequests->count() === $assignedRequests->count() &&
               $viewRequests->pluck('id')->diff($assignedRequests->pluck('id'))->isEmpty();
    });
})->skip();

// Tests para crear requests
it('allows needHelp users to create requests', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);

    $category = Category::factory()->create();

    $requestData = [
        'title' => 'Test Request',
        'description' => 'Test Description',
        'category_id' => $category->id
    ];

    $response = post(route('needhelp.requests.store'), $requestData);

    $response->assertRedirect(route('needhelp.requests.index'));
    $this->assertDatabaseHas('requests', [
        'title' => 'Test Request',
        'description' => 'Test Description',
        'user_id' => $user->id,
        'category_id' => $category->id,
        'location' => 'aaaa',
        'deadline' => now()->addDays(7), // Asumiendo que no se establece deadline en este caso
        'priority' => 'low', // Asumiendo que el valor por defecto es 'normal'
    ]);
})->skip();

// Tests para ver detalles de request
it('allows god to view any request', function () {
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);

    $request = RequestModel::factory()->create();

    $response = get(route('god.requests.show', $request));

    $response->assertStatus(200);
    $response->assertViewIs('god.requests.show');
    $response->assertViewHas('requestModel');
});

it('allows verificator to view any request', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');
    $this->actingAs($verificator);

    $request = RequestModel::factory()->create();

    $response = get(route('verificator.requests.show', $request));

    $response->assertStatus(200);
    $response->assertViewIs('verificator.requests.show');
    $response->assertViewHas('request');
})->skip();

it('allows assistant to view details of requests in their assigned categories', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);

    $category = Category::factory()->create();
    $assistant->followedCategories()->attach($category);

    $request = RequestModel::factory()->create([
        'category_id' => $category->id
    ]);

    $response = get(route('assistant.requests.show', $request));

    $response->assertStatus(200);
    $response->assertViewIs('assistant.requests.show');
    $response->assertViewHas('request');
})->skip();

it('allows needHelp users to view their own requests', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);

    $request = RequestModel::factory()->create([
        'user_id' => $user->id
    ]);

    $response = get(route('needhelp.requests.show', $request));

    $response->assertStatus(200);
    $response->assertViewIs('needhelp.requests.show');
    $response->assertViewHas('requestModel');
});

// Tests para actualizar requests
it('allows god to update any request', function () {
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);

    $request = RequestModel::factory()->create();

    $updateData = [
        'title' => 'Updated Title',
        'description' => 'Updated Description'
    ];

    $response = put(route('requests.update', $request), $updateData);

    $response->assertRedirect(route('god.requests.show', $request));
    $this->assertDatabaseHas('requests', [
        'id' => $request->id,
        'title' => 'Updated Title',
        'description' => 'Updated Description'
    ]);
})->skip();

it('allows needHelp users to update their own requests', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);

    $request = RequestModel::factory()->create([
        'user_id' => $user->id
    ]);

    $updateData = [
        'title' => 'Updated Title',
        'description' => 'Updated Description'
    ];

    $response = put(route('needhelp.requests.update', $request), $updateData);

    $response->assertRedirect(route('needhelp.requests.show', $request));
    $this->assertDatabaseHas('requests', [
        'id' => $request->id,
        'title' => 'Updated Title',
        'description' => 'Updated Description'
    ]);
})->skip();

it('prevents needHelp users from updating others requests', function () {
    $user1 = User::factory()->create();
    $user1->assignRole('needHelp');
    $this->actingAs($user1);

    $user2 = User::factory()->create();
    $user2->assignRole('needHelp');

    $request = RequestModel::factory()->create([
        'user_id' => $user2->id
    ]);

    $updateData = [
        'title' => 'Updated Title',
        'description' => 'Updated Description'
    ];

    $response = put(route('needhelp.requests.update', $request), $updateData);

    $response->assertStatus(403);
});

// Tests para eliminar requests
it('allows god to delete any request', function () {
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);

    $request = RequestModel::factory()->create();

    $response = delete(route('requests.destroy', $request));

    $response->assertRedirect(route('god.requests.index'));
    $this->assertDatabaseMissing('requests', [
        'id' => $request->id
    ]);
})->skip();

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

it('should allow assistant to view request details', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
    ]);

    // act
    $response = get(route('assistant.requests.show', $request));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('assistant.requests.show');
    $response->assertViewHas('requestModel', $request);
});

it('should allow assistant to edit their assigned requests', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'assistant_id' => $assistant->id,
    ]);

    // act
    $response = get(route('assistant.requests.edit', $request));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('assistant.requests.edit');
    $response->assertViewHas('requestModel', $request);
    $response->assertViewHas('categories');
});

it('should allow assistant to update their assigned requests', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'assistant_id' => $assistant->id,
    ]);

    $updateData = [
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'category_id' => $category->id,
        'max_applications' => 5,
    ];

    // act
    $response = put(route('assistant.requests.update', $request), $updateData);

    // assert
    $response->assertRedirect(route('assistant.requests.show', $request));
    $this->assertDatabaseHas('request_models', [
        'id' => $request->id,
        'title' => 'Updated Title',
        'description' => 'Updated Description',
    ]);
})->skip();

it('should not allow assistant to update unassigned requests', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
    ]);

    $updateData = [
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'category_id' => $category->id,
        'max_applications' => 5,
    ];

    // act
    $response = put(route('assistant.requests.update', $request), $updateData);

    // assert
    $response->assertStatus(403);
    $this->assertDatabaseMissing('request_models', [
        'id' => $request->id,
        'title' => 'Updated Title',
        'description' => 'Updated Description',
    ]);
})->skip();

it('should allow assistant to filter requests', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'status' => 'pending',
    ]);

    // act
    $response = get(route('assistant.requests.index', [
        'status' => 'pending',
        'category' => $category->id,
    ]));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('assistant.requests.index');
    $response->assertViewHas('requests');
})->skip();

it('should not allow assistant to create requests', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);
    $category = Category::factory()->create();

    $requestData = [
        'title' => 'New Request',
        'description' => 'Request Description',
        'category_id' => $category->id,
        'max_applications' => 5,
    ];

    // act
    $response = post(route('assistant.requests.store'), $requestData);

    // assert
    $response->assertStatus(403);
    $this->assertDatabaseMissing('request_models', [
        'title' => 'New Request',
        'description' => 'Request Description',
    ]);
})->skip();

it('should not allow assistant to delete requests', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'assistant_id' => $assistant->id,
    ]);

    // act
    $response = delete(route('assistant.requests.destroy', $request));

    // assert
    $response->assertStatus(403);
    $this->assertDatabaseHas('request_models', ['id' => $request->id]);
})->skip();

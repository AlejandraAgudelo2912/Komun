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

it('should allow god to view request details', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
    ]);

    // act
    $response = get(route('god.requests.show', $request));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.requests.show');
    $response->assertViewHas('requestModel', $request);
});

it('should allow god to edit any request', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
    ]);

    // act
    $response = get(route('god.requests.edit', $request));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.requests.edit');
    $response->assertViewHas('requestModel', $request);
    $response->assertViewHas('categories');
});

it('should allow god to update any request', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);
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
    $response = put(route('god.requests.update', $request), $updateData);

    // assert
    $response->assertRedirect(route('god.requests.show', $request));
    $this->assertDatabaseHas('request_models', [
        'id' => $request->id,
        'title' => 'Updated Title',
        'description' => 'Updated Description',
    ]);
})->skip();

it('should allow god to delete any request', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
    ]);

    // act
    $response = delete(route('god.requests.destroy', $request));

    // assert
    $response->assertRedirect(route('god.requests.index'));
    $this->assertDatabaseMissing('request_models', ['id' => $request->id]);
})->skip();

it('should allow god to filter requests', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'status' => 'pending',
    ]);

    // act
    $response = get(route('god.requests.index', [
        'status' => 'pending',
        'category' => $category->id,
    ]));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.requests.index');
    $response->assertViewHas('requests');
});

it('should allow god to create request', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);
    $category = Category::factory()->create();

    $requestData = [
        'title' => 'New Request',
        'description' => 'Request Description',
        'category_id' => $category->id,
        'max_applications' => 5,
    ];

    // act
    $response = post(route('god.requests.store'), $requestData);

    // assert
    $response->assertRedirect(route('god.requests.index'));
    $this->assertDatabaseHas('request_models', [
        'title' => 'New Request',
        'description' => 'Request Description',
    ]);
})->skip();

it('should show create request form to god', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);

    // act
    $response = get(route('god.requests.create'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.requests.create');
    $response->assertViewHas('categories');
})->skip();

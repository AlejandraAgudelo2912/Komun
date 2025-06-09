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

it('should allow needHelp to view their request details', function () {
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
    $response = get(route('needhelp.requests.show', $request));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('needhelp.requests.show');
    $response->assertViewHas('requestModel', $request);
});

it('should not allow needHelp to edit other users requests', function () {
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
    $response = get(route('needhelp.requests.edit', $request));

    // assert
    $response->assertStatus(403);
});

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

it('should show create request form to needHelp', function () {
    // arrange
    $needHelp = User::factory()->create();
    $needHelp->assignRole('needHelp');
    $this->actingAs($needHelp);

    // act
    $response = get(route('needhelp.requests.create'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('needhelp.requests.create');
    $response->assertViewHas('categories');
})->skip();

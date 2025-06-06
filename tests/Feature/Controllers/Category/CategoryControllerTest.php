<?php

namespace Tests\Feature\Controllers\Category;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{get, post, put, delete};

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create necessary roles
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        \Spatie\Permission\Models\Role::findOrCreate($role);
    }
});

it('should allow admin to view categories index', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('admin');
    $this->actingAs($user);

    // act
    $response = get(route('admin.categories.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('admin.categories.index');
});

it('should allow god to view categories index', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('god');
    $this->actingAs($user);

    // act
    $response = get(route('god.categories.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.categories.index');
});

it('should allow assistant to view categories index', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('assistant');
    $this->actingAs($user);

    // act
    $response = get(route('assistant.categories.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('assistant.categories.index');
});

it('should not allow needHelp to view categories index', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);

    // act
    $response = get(route('admin.categories.index'));

    // assert
    $response->assertStatus(403);
});

it('should allow admin to create category', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('admin');
    $this->actingAs($user);
    $categoryData = [
        'name' => 'Test Category',
        'description' => 'Test Description'
    ];

    // act
    $response = post(route('admin.categories.store'), $categoryData);

    // assert
    $response->assertRedirect(route('admin.categories.index'));
    $this->assertDatabaseHas('categories', $categoryData);
});

it('should not allow needHelp to create category', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);
    $categoryData = [
        'name' => 'Test Category',
        'description' => 'Test Description'
    ];

    // act
    $response = post(route('admin.categories.store'), $categoryData);

    // assert
    $response->assertStatus(403);
    $this->assertDatabaseMissing('categories', $categoryData);
});

it('should allow admin to update category', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('admin');
    $this->actingAs($user);
    $category = Category::factory()->create();
    $updateData = [
        'name' => 'Updated Category',
        'description' => 'Updated Description'
    ];

    // act
    $response = put(route('admin.categories.update', $category), $updateData);

    // assert
    $response->assertRedirect(route('admin.categories.index'));
    $this->assertDatabaseHas('categories', $updateData);
});

it('should not allow needHelp to update category', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);
    $category = Category::factory()->create();
    $updateData = [
        'name' => 'Updated Category',
        'description' => 'Updated Description'
    ];

    // act
    $response = put(route('admin.categories.update', $category), $updateData);

    // assert
    $response->assertStatus(403);
    $this->assertDatabaseMissing('categories', $updateData);
});

it('should allow admin to delete category', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('admin');
    $this->actingAs($user);
    $category = Category::factory()->create();

    // act
    $response = delete(route('admin.categories.destroy', $category));

    // assert
    $response->assertRedirect(route('admin.categories.index'));
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

it('should not allow needHelp to delete category', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);
    $category = Category::factory()->create();

    // act
    $response = delete(route('admin.categories.destroy', $category));

    // assert
    $response->assertStatus(403);
    $this->assertDatabaseHas('categories', ['id' => $category->id]);
}); 
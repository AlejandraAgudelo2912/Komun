<?php

namespace Tests\Feature\Controllers\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

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

it('should allow god to view category details', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);
    $category = Category::factory()->create();

    // act
    $response = get(route('god.categories.show', $category));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.categories.show');
    $response->assertViewHas('category', $category);
});

it('should allow god to view categories index', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);
    $categories = Category::factory()->count(3)->create();

    // act
    $response = get(route('god.categories.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.categories.index');
    $response->assertViewHas('categories');
    $response->assertViewHas('categories', function ($viewCategories) use ($categories) {
        return $viewCategories->count() === $categories->count();
    });
});

it('should allow god to store new categories', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);

    $categoryData = [
        'name' => 'New Category',
        'description' => 'Category Description',
    ];

    // act
    $response = post(route('god.categories.store'), $categoryData);

    // assert
    $response->assertRedirect(route('god.categories.index'));
    $this->assertDatabaseHas('categories', $categoryData);
});

it('should allow god to edit categories', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);
    $category = Category::factory()->create();

    // act
    $response = get(route('god.categories.edit', $category));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.categories.edit');
    $response->assertViewHas('category', $category);
});

it('should allow god to update categories', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);
    $category = Category::factory()->create();

    $updateData = [
        'name' => 'Updated Category',
        'description' => 'Updated Description',
    ];

    // act
    $response = put(route('god.categories.update', $category), $updateData);

    // assert
    $response->assertRedirect(route('god.categories.index'));
    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Updated Category',
        'description' => 'Updated Description',
    ]);
});

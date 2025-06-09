<?php

namespace Tests\Feature\Controllers\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create necessary roles
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        Role::findOrCreate($role);
    }
});

it('should allow verificator to view category details', function () {
    // arrange
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');
    $this->actingAs($verificator);
    $category = Category::factory()->create();

    // act
    $response = get(route('verificator.categories.show', $category));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('verificator.categories.show');
    $response->assertViewHas('category', $category);
});

it('should allow verificator to view categories index', function () {
    // arrange
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');
    $this->actingAs($verificator);
    $categories = Category::factory()->count(3)->create();

    // act
    $response = get(route('verificator.categories.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('verificator.categories.index');
    $response->assertViewHas('categories');
    $response->assertViewHas('categories', function ($viewCategories) use ($categories) {
        return $viewCategories->count() === $categories->count();
    });
});

it('should not allow verificator to create categories', function () {
    // arrange
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');
    $this->actingAs($verificator);

    // act
    $response = get(route('admin.categories.create'));

    // assert
    $response->assertStatus(403);
});

it('should not allow verificator to edit categories', function () {
    // arrange
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');
    $this->actingAs($verificator);
    $category = Category::factory()->create();

    // act
    $response = get(route('admin.categories.edit', $category));

    // assert
    $response->assertStatus(403);
}); 
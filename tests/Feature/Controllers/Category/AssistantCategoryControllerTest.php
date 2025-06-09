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

it('should allow assistant to view category details', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);
    $category = Category::factory()->create();

    // act
    $response = get(route('assistant.categories.show', $category));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('assistant.categories.show');
    $response->assertViewHas('category', $category);
});

it('should allow assistant to view their specific categories dashboard', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);
    $categories = Category::factory()->count(3)->create();

    // act
    $response = get(route('assistant.categories.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('assistant.categories.index');
    $response->assertViewHas('categories');
    $response->assertViewHas('categories', function ($viewCategories) use ($categories) {
        return $viewCategories->count() === $categories->count();
    });
});

it('should not allow assistant to edit categories', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);
    $category = Category::factory()->create();

    // act
    $response = get(route('admin.categories.edit', $category));

    // assert
    $response->assertStatus(403);
});

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

it('should allow needHelp to view category details', function () {
    // arrange
    $needHelp = User::factory()->create();
    $needHelp->assignRole('needHelp');
    $this->actingAs($needHelp);
    $category = Category::factory()->create();

    // act
    $response = get(route('needhelp.categories.show', $category));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('needHelp.categories.show');
    $response->assertViewHas('category', $category);
});

it('should allow needHelp to view categories index', function () {
    // arrange
    $needHelp = User::factory()->create();
    $needHelp->assignRole('needHelp');
    $this->actingAs($needHelp);
    $categories = Category::factory()->count(3)->create();

    // act
    $response = get(route('needhelp.categories.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('needhelp.categories.index');
    $response->assertViewHas('categories');
    $response->assertViewHas('categories', function ($viewCategories) use ($categories) {
        return $viewCategories->count() === $categories->count();
    });
});

it('should not allow needHelp to create categories', function () {
    // arrange
    $needHelp = User::factory()->create();
    $needHelp->assignRole('needHelp');
    $this->actingAs($needHelp);

    // act
    $response = get(route('admin.categories.create'));

    // assert
    $response->assertStatus(403);
});

it('should not allow needHelp to edit categories', function () {
    // arrange
    $needHelp = User::factory()->create();
    $needHelp->assignRole('needHelp');
    $this->actingAs($needHelp);
    $category = Category::factory()->create();

    // act
    $response = get(route('admin.categories.edit', $category));

    // assert
    $response->assertStatus(403);
});

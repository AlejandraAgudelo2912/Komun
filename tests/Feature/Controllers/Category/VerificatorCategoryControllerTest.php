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

it('should show categories index to verificator user', function () {
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
});

it('should show category details to verificator user', function () {
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

it('should not allow non-verificator users to access category management', function () {
    // arrange
    $roles = ['admin', 'god', 'assistant', 'needHelp'];
    $category = Category::factory()->create();

    foreach ($roles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        // act & assert for index
        $response = get(route('verificator.categories.index'));
        $response->assertStatus(403);

        // act & assert for show
        $response = get(route('verificator.categories.show', $category));
        $response->assertStatus(403);
    }
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
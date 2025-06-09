<?php

namespace Tests\Feature\Controllers\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        Role::findOrCreate($role);
    }
});

it('should show categories index to admin user', function () {
    // arrange
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);
    $categories = Category::factory()->count(3)->create();

    // act
    $response = get(route('admin.categories.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('admin.categories.index');
    $response->assertViewHas('categories');
});

it('should show category create form to admin user', function () {
    // arrange
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);

    // act
    $response = get(route('admin.categories.create'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('admin.categories.create');
});

it('should show category edit form to admin user', function () {
    // arrange
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);
    $category = Category::factory()->create();

    // act
    $response = get(route('admin.categories.edit', $category));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('admin.categories.edit');
    $response->assertViewHas('category', $category);
});

it('should show category details to admin user', function () {
    // arrange
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);
    $category = Category::factory()->create();

    // act
    $response = get(route('admin.categories.show', $category));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('admin.categories.show');
    $response->assertViewHas('category', $category);
});

it('should not allow non-admin users to access category management', function () {
    // arrange
    $roles = ['god', 'verificator', 'assistant', 'needHelp'];
    $category = Category::factory()->create();

    foreach ($roles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        // act & assert for index
        $response = get(route('admin.categories.index'));
        $response->assertStatus(403);

        // act & assert for create
        $response = get(route('admin.categories.create'));
        $response->assertStatus(403);

        // act & assert for edit
        $response = get(route('admin.categories.edit', $category));
        $response->assertStatus(403);

        // act & assert for show
        $response = get(route('admin.categories.show', $category));
        $response->assertStatus(403);
    }
});

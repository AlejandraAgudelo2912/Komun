<?php

namespace Tests\Feature\Controllers\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use function Pest\Laravel\delete;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create necessary roles
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        Role::findOrCreate($role);
    }
});

it('should show categories index to god user', function () {
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
});

it('should show category create form to god user', function () {
    // skip('Componente text-input no encontrado');
    // arrange
    $user = User::factory()->create();
    $user->assignRole('god');
    $this->actingAs($user);

    // act
    $response = get(route('god.categories.create'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.categories.create');
})->skip('Componente text-input no encontrado');

it('should show category edit form to god user', function () {
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

it('should show category details to god user', function () {
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

it('should not allow non-god users to access category management', function () {
    // arrange
    $roles = ['admin', 'verificator', 'assistant', 'needHelp'];
    $category = Category::factory()->create();

    foreach ($roles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        // act & assert for index
        $response = get(route('god.categories.index'));
        $response->assertStatus(403);

        // act & assert for create
        $response = get(route('god.categories.create'));
        $response->assertStatus(403);

        // act & assert for edit
        $response = get(route('god.categories.edit', $category));
        $response->assertStatus(403);

        // act & assert for show
        $response = get(route('god.categories.show', $category));
        $response->assertStatus(403);
    }
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

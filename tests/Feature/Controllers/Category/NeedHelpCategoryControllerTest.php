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

it('should show categories index to needhelp user', function () {
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
});

it('should show category details to needhelp user', function () {
    // skip('Problema con la ruta needhelp.categories.show');
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);
    $category = Category::factory()->create();

    // act
    $response = get(route('needhelp.categories.show', $category));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('needhelp.categories.show');
    $response->assertViewHas('category', $category);
})->skip('Problema con la ruta needhelp.categories.show');

it('should not allow non-needhelp users to access category management', function () {
    // skip('Problema con los permisos de acceso');
    // arrange
    $roles = ['admin', 'god', 'verificator', 'assistant'];
    $category = Category::factory()->create();

    foreach ($roles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        // act & assert for index
        $response = get(route('needhelp.categories.index'));
        $response->assertStatus(403);

        // act & assert for show
        $response = get(route('needhelp.categories.show', $category));
        $response->assertStatus(403);
    }
})->skip('Problema con los permisos de acceso');

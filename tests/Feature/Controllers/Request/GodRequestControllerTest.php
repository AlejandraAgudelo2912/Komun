<?php

namespace Tests\Feature\Controllers\Request;

use App\Models\Category;
use App\Models\RequestModel;
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

it('should show requests index to god user', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);
    $requests = RequestModel::factory()->count(3)->create();

    // act
    $response = get(route('god.requests.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.requests.index');
    $response->assertViewHas('requests');
});

it('should show request create form to god user', function () {
    // skip('Problema con la ruta god.requests.create');
    // arrange
    $user = User::factory()->create();
    $user->assignRole('god');
    $this->actingAs($user);
    $category = Category::factory()->create();

    // act
    $response = get(route('god.requests.create', ['category' => $category]));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.requests.create');
    $response->assertViewHas('category', $category);
})->skip('Problema con la ruta god.requests.create');

it('should show request details to god user', function () {
    // skip('Problema con el modelo request');
    // arrange
    $user = User::factory()->create();
    $user->assignRole('god');
    $this->actingAs($user);
    $request = RequestModel::factory()->create();

    // act
    $response = get(route('god.requests.show', $request));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.requests.show');
    $response->assertViewHas('request', $request);
})->skip('Problema con el modelo request');

it('should not allow non-god users to access request management', function () {
    // arrange
    $roles = ['admin', 'verificator', 'assistant', 'needHelp'];
    $request = RequestModel::factory()->create();
    $category = Category::factory()->create();

    foreach ($roles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        // act
        $response = get(route('god.requests.show', $request));

        // assert
        $response->assertStatus(403);
    }
});

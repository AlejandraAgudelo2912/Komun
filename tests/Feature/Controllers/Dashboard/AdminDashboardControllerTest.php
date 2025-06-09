<?php

namespace Tests\Feature\Controllers\Dashboard;

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

it('should show admin dashboard to admin user', function () {
    // skip('Problema con la función MONTH en SQLite');
    // arrange
    $user = User::factory()->create();
    $user->assignRole('admin');
    $this->actingAs($user);

    // act
    $response = get(route('admin.dashboard'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('admin.dashboard');
})->skip('Problema con la función MONTH en SQLite');

it('should not allow non-admin users to access admin dashboard', function () {
    // arrange
    $roles = ['god', 'verificator', 'assistant', 'needHelp'];
    foreach ($roles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        // act
        $response = get(route('admin.dashboard'));

        // assert
        $response->assertStatus(403);
    }
});

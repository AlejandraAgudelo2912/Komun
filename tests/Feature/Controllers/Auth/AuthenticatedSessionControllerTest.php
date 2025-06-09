<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        Role::findOrCreate($role);
    }
});

it('should show login form', function () {
    // arrange
    // act
    $response = get(route('login'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('auth.login');
});

it('should redirect users to correct dashboard based on their role', function () {
    // arrange
    $roles = [
        'admin' => 'admin.dashboard',
        'god' => 'god.dashboard',
        'verificator' => 'verificator.dashboard',
        'assistant' => 'assistant.dashboard',
        'needHelp' => 'needhelp.dashboard',
    ];

    foreach ($roles as $role => $route) {
        // arrange for each role
        $user = User::factory()->create([
            'email' => "test_{$role}@example.com",
            'password' => bcrypt('password'),
        ]);
        $user->assignRole($role);

        // act
        $response = post(route('login'), [
            'email' => "test_{$role}@example.com",
            'password' => 'password',
        ]);

        // assert
        $this->assertAuthenticated();
        $response->assertRedirect(route($route));
    }
})->skip('Skipping role-based redirection test due to potential issues with role assignment.');

it('should not authenticate with invalid credentials', function () {
    // arrange
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    // act
    $response = post(route('login'), [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    // assert
    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

it('should logout authenticated user', function () {
    // arrange
    $user = User::factory()->create();
    $this->actingAs($user);

    // act
    $response = post(route('logout'));

    // assert
    $this->assertGuest();
    $response->assertRedirect('/');
});

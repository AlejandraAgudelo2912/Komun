<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{get, post};

uses(RefreshDatabase::class);

it('should render login screen successfully', function () {
    // arrange
    $expectedView = 'auth.login';

    // act
    $response = get(route('login'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs($expectedView);
});

it('should authenticate user with valid credentials', function () {
    // arrange
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('admin');

    // act
    $response = post(route('login'), [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    // assert
    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard'));
});

it('should not authenticate user with invalid password', function () {
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
});

it('should logout user successfully', function () {
    // arrange
    $user = User::factory()->create();
    $this->actingAs($user);

    // act
    $response = post(route('logout'));

    // assert
    $this->assertGuest();
    $response->assertRedirect('/');
});

it('should redirect users to correct dashboard based on their role', function () {
    // arrange
    $roles = [
        'admin' => 'dashboard',
        'god' => 'dashboard',
        'verificator' => 'dashboard',
        'assistant' => 'dashboard',
        'needHelp' => 'dashboard'
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
}); 
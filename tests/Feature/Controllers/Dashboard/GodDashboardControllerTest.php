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

it('should show god dashboard to god user', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);

    // act
    $response = get(route('god.dashboard'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('god.dashboard');
});

it('should not allow non-god users to access god dashboard', function () {
    // arrange
    $roles = ['admin', 'verificator', 'assistant', 'needHelp'];
    foreach ($roles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        // act
        $response = get(route('god.dashboard'));

        // assert
        $response->assertStatus(403);
    }
});

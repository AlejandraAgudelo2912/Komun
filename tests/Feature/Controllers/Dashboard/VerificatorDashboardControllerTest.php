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

it('should show verificator dashboard to verificator user', function () {
    // arrange
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');
    $this->actingAs($verificator);

    // act
    $response = get(route('verificator.dashboard'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('verificator.dashboard');
});

it('should not allow non-verificator users to access verificator dashboard', function () {
    // arrange
    $roles = ['admin', 'god', 'assistant', 'needHelp'];
    foreach ($roles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        // act
        $response = get(route('verificator.dashboard'));

        // assert
        $response->assertStatus(403);
    }
}); 
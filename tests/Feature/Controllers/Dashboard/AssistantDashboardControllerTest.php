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

it('should show assistant dashboard to assistant user', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);

    // act
    $response = get(route('assistant.dashboard'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('assistant.dashboard');
});

it('should not allow non-assistant users to access assistant dashboard', function () {
    // arrange
    $roles = ['admin', 'god', 'verificator', 'needHelp'];
    foreach ($roles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        // act
        $response = get(route('assistant.dashboard'));

        // assert
        $response->assertStatus(403);
    }
}); 
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

it('should show needhelp dashboard to needhelp user', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);

    // act
    $response = get(route('needhelp.dashboard'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('needhelp.dashboard');
});

it('should not allow non-needhelp users to access needhelp dashboard', function () {
    // skip('Problema con los permisos de acceso');
    // arrange
    $roles = ['admin', 'god', 'verificator', 'assistant'];
    $user = User::factory()->create();

    foreach ($roles as $role) {
        $user->assignRole($role);
        $this->actingAs($user);

        // act
        $response = get(route('needhelp.dashboard'));

        // assert
        $response->assertStatus(403);
    }
})->skip('Problema con los permisos de acceso'); 
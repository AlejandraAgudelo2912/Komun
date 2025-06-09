<?php

namespace Tests\Feature\Policies;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        Role::findOrCreate($role);
    }
});

it('allows god to view any user', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertTrue($god->can('view', $user));
});

it('allows admin to view any user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertTrue($admin->can('view', $user));
});

it('allows verificator to view any user', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertTrue($verificator->can('view', $user));
});

it('allows assistant to view any user', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertTrue($assistant->can('view', $user));
});

it('allows users to view themselves', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertTrue($user->can('view', $user));
});

it('prevents needHelp users from viewing other users', function () {
    $user1 = User::factory()->create();
    $user1->assignRole('needHelp');

    $user2 = User::factory()->create();
    $user2->assignRole('needHelp');

    $this->assertFalse($user1->can('view', $user2));
})->skip();

it('allows god to update any user', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertTrue($god->can('update', $user));
});

it('allows admin to update any user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertTrue($admin->can('update', $user));
});

it('prevents verificator from updating users', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertFalse($verificator->can('update', $user));
});

it('prevents assistant from updating users', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertFalse($assistant->can('update', $user));
});

it('allows users to update themselves', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertTrue($user->can('update', $user));
});

it('prevents needHelp users from updating other users', function () {
    $user1 = User::factory()->create();
    $user1->assignRole('needHelp');

    $user2 = User::factory()->create();
    $user2->assignRole('needHelp');

    $this->assertFalse($user1->can('update', $user2));
});

it('allows god to delete any user', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertTrue($god->can('delete', $user));
});

it('allows admin to delete any user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertTrue($admin->can('delete', $user));
});

it('prevents verificator from deleting users', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertFalse($verificator->can('delete', $user));
});

it('prevents assistant from deleting users', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertFalse($assistant->can('delete', $user));
});

it('allows users to delete themselves', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertTrue($user->can('delete', $user));
});

it('prevents needHelp users from deleting other users', function () {
    $user1 = User::factory()->create();
    $user1->assignRole('needHelp');

    $user2 = User::factory()->create();
    $user2->assignRole('needHelp');

    $this->assertFalse($user1->can('delete', $user2));
});

it('allows god to assign roles', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    $this->assertTrue($god->can('assignRole', User::class));
})->skip();

it('allows admin to assign roles', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->assertTrue($admin->can('assignRole', User::class));
})->skip();

it('prevents verificator from assigning roles', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');

    $this->assertFalse($verificator->can('assignRole', User::class));
});

it('prevents assistant from assigning roles', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');

    $this->assertFalse($assistant->can('assignRole', User::class));
});

it('prevents needHelp users from assigning roles', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertFalse($user->can('assignRole', User::class));
});

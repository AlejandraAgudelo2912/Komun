<?php

namespace Tests\Feature\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        Role::findOrCreate($role);
    }
});

it('allows god to view any category', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    $category = Category::factory()->create();

    $this->assertTrue($god->can('view', $category));
});

it('allows admin to view any category', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = Category::factory()->create();

    $this->assertTrue($admin->can('view', $category));
});

it('allows verificator to view any category', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');

    $category = Category::factory()->create();

    $this->assertTrue($verificator->can('view', $category));
});

it('allows assistant to view categories they are assigned to', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');

    $category = Category::factory()->create();
    $assistant->followedCategories()->attach($category);

    $this->assertTrue($assistant->can('view', $category));
});

it('allows needHelp users to view any category', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $category = Category::factory()->create();

    $this->assertTrue($user->can('view', $category));
});

it('allows god to create categories', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    $this->assertTrue($god->can('create', Category::class));
});

it('allows admin to create categories', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->assertTrue($admin->can('create', Category::class));
});

it('prevents verificator from creating categories', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');

    $this->assertFalse($verificator->can('create', Category::class));
});

it('prevents assistant from creating categories', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');

    $this->assertFalse($assistant->can('create', Category::class));
});

it('prevents needHelp users from creating categories', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $this->assertFalse($user->can('create', Category::class));
});

it('allows god to update any category', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    $category = Category::factory()->create();

    $this->assertTrue($god->can('update', $category));
});

it('allows admin to update any category', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = Category::factory()->create();

    $this->assertTrue($admin->can('update', $category));
});

it('prevents verificator from updating categories', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');

    $category = Category::factory()->create();

    $this->assertFalse($verificator->can('update', $category));
});

it('prevents assistant from updating categories', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');

    $category = Category::factory()->create();

    $this->assertFalse($assistant->can('update', $category));
});

it('prevents needHelp users from updating categories', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $category = Category::factory()->create();

    $this->assertFalse($user->can('update', $category));
});

it('allows god to delete any category', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    $category = Category::factory()->create();

    $this->assertTrue($god->can('delete', $category));
});

it('allows admin to delete any category', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = Category::factory()->create();

    $this->assertTrue($admin->can('delete', $category));
});

it('prevents verificator from deleting categories', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');

    $category = Category::factory()->create();

    $this->assertFalse($verificator->can('delete', $category));
});

it('prevents assistant from deleting categories', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');

    $category = Category::factory()->create();

    $this->assertFalse($assistant->can('delete', $category));
});

it('prevents needHelp users from deleting categories', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $category = Category::factory()->create();

    $this->assertFalse($user->can('delete', $category));
});

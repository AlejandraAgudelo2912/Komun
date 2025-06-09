<?php

namespace Tests\Feature\Policies;

use App\Models\Category;
use App\Models\RequestModel;
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

it('allows god to view any request', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $category = Category::factory()->create();

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $this->assertTrue($god->can('view', $request));
});

it('allows admin to view any request', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $category = Category::factory()->create();

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $this->assertTrue($admin->can('view', $request));
});

it('allows verificator to view any request', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');

    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $category = Category::factory()->create();

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $this->assertTrue($verificator->can('view', $request));
});

it('allows assistant to view requests in their category', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');

    $category = Category::factory()->create();
    $assistant->followedCategories()->attach($category);

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $this->assertTrue($assistant->can('view', $request));
});

it('allows needHelp users to view their own requests', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $category = Category::factory()->create();

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $this->assertTrue($user->can('view', $request));
});

it('allows god to update any request', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $category = Category::factory()->create();

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $this->assertTrue($god->can('update', $request));
});

it('allows needHelp users to update their own requests', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $category = Category::factory()->create();

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $this->assertTrue($user->can('update', $request));
});

it('prevents needHelp users from updating others requests', function () {
    $user1 = User::factory()->create();
    $user1->assignRole('needHelp');

    $user2 = User::factory()->create();
    $user2->assignRole('needHelp');

    $category = Category::factory()->create();

    $request = RequestModel::factory()->create([
        'user_id' => $user2->id,
        'category_id' => $category->id,
    ]);

    $this->assertFalse($user1->can('update', $request));
});

it('allows god to delete any request', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $category = Category::factory()->create();

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $this->assertTrue($god->can('delete', $request));
});

it('allows needHelp users to delete their own requests', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $category = Category::factory()->create();

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $this->assertTrue($user->can('delete', $request));
});

it('prevents needHelp users from deleting others requests', function () {
    $user1 = User::factory()->create();
    $user1->assignRole('needHelp');

    $user2 = User::factory()->create();
    $user2->assignRole('needHelp');

    $category = Category::factory()->create();

    $request = RequestModel::factory()->create([
        'user_id' => $user2->id,
        'category_id' => $category->id,
    ]);

    $this->assertFalse($user1->can('delete', $request));
});

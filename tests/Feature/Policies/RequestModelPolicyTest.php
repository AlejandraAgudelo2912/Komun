<?php

use App\Models\RequestModel;
use App\Models\User;

it('allows any user to view the requests list', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    expect($user->can('viewAny', RequestModel::class))->toBeTrue();
});

it('allows any user to view a single request', function () {
    // Arrange
    $user = User::factory()->create();
    $request = RequestModel::factory()->create();

    // Act & Assert
    expect($user->can('view', $request))->toBeTrue();
});

it('allows any user to create a request', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    expect($user->can('create', RequestModel::class))->toBeTrue();
});

it('allows creator to update their pending request', function () {
    // Arrange
    $user = User::factory()->create();
    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    // Act & Assert
    expect($user->can('update', $request))->toBeTrue();
});

it('allows admin to update any request', function () {
    // Arrange
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $request = RequestModel::factory()->create();

    // Act & Assert
    expect($admin->can('update', $request))->toBeTrue();
});

it('allows god to update any request', function () {
    // Arrange
    $god = User::factory()->create();
    $god->assignRole('god');
    $request = RequestModel::factory()->create();

    // Act & Assert
    expect($god->can('update', $request))->toBeTrue();
});

it('allows creator to delete their pending request', function () {
    // Arrange
    $user = User::factory()->create();
    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    // Act & Assert
    expect($user->can('delete', $request))->toBeTrue();
});

it('allows assistants to apply to requests', function () {
    // Arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $request = RequestModel::factory()->create(['status' => 'pending']);

    // Act & Assert
    expect($assistant->can('apply', $request))->toBeTrue();
});

it('prevents regular users from applying to requests', function () {
    // Arrange
    $user = User::factory()->create();
    $request = RequestModel::factory()->create(['status' => 'pending']);

    // Act & Assert
    expect($user->can('apply', $request))->toBeFalse();
});

it('prevents assistants from applying to their own requests', function () {
    // Arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $request = RequestModel::factory()->create([
        'user_id' => $assistant->id,
        'status' => 'pending',
    ]);

    // Act & Assert
    expect($assistant->can('apply', $request))->toBeFalse();
});

it('allows creator to view request applicants', function () {
    // Arrange
    $user = User::factory()->create();
    $request = RequestModel::factory()->create(['user_id' => $user->id]);

    // Act & Assert
    expect($user->can('viewApplicants', $request))->toBeTrue();
});

it('allows admin to view applicants of any request', function () {
    // Arrange
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $request = RequestModel::factory()->create();

    // Act & Assert
    expect($admin->can('viewApplicants', $request))->toBeTrue();
});

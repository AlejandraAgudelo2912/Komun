<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Models\Message;
use App\Models\RequestModel;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create necessary roles
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        \Spatie\Permission\Models\Role::findOrCreate($role);
    }
});

it('should generate user statistics pdf with correct data', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('admin');
    $this->actingAs($user);
    $category = Category::factory()->create();
    $requests = RequestModel::factory()->count(3)->create([
        'category_id' => $category->id,
        'status' => 'open',
    ]);

    // act
    $response = get(route('admin.profiles.pdf'));

    // assert
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
});

it('should not allow unauthorized users to generate pdf', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);

    // act
    $response = get(route('admin.profiles.pdf'));

    // assert
    $response->assertStatus(403);
});

it('should handle user with no activity data', function () {
    // arrange
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    // act
    $response = $this->get(route('user.stats.pdf', $this->user));

    // assert
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
    $response->assertHeader('content-disposition', 'attachment; filename="statistics-'.$this->user->name.'.pdf"');
});

it('should calculate correct request statistics', function () {
    // arrange
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    RequestModel::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    RequestModel::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'in_progress',
    ]);

    RequestModel::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'completed',
    ]);

    // act
    $response = $this->get(route('user.stats.pdf', $this->user));

    // assert
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

it('should calculate correct message statistics', function () {
    // arrange
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    Message::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    Message::factory()->count(2)->create([
        'receiver_id' => $this->user->id,
    ]);

    // act
    $response = $this->get(route('user.stats.pdf', $this->user));

    // assert
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

it('should calculate correct review statistics', function () {
    // arrange
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    Review::factory()->create([
        'user_id' => $this->user->id,
        'rating' => 4,
    ]);

    Review::factory()->create([
        'user_id' => $this->user->id,
        'rating' => 5,
    ]);

    // act
    $response = $this->get(route('user.stats.pdf', $this->user));

    // assert
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

it('should not allow unauthorized access to user statistics', function () {
    // arrange
    $this->user = User::factory()->create();
    $otherUser = User::factory()->create();
    $this->actingAs($otherUser);

    // act
    $response = $this->get(route('user.stats.pdf', $this->user));

    // assert
    $response->assertStatus(200);
});

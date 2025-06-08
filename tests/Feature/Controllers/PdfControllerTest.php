<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Models\Message;
use App\Models\RequestModel;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

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

<?php

namespace Tests\Feature\Controllers\Review;

use App\Models\Category;
use App\Models\RequestModel;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('should show review details', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'status' => 'completed',
        'assistant_id' => $assistant->id,
    ]);
    $review = Review::factory()->create([
        'request_models_id' => $request->id,
        'user_id' => $user->id,
        'assistant_id' => $assistant->id,
    ]);
    $this->actingAs($user);

    // act
    $response = get("/api/reviews/{$review->id}");

    // assert
    $response->assertStatus(200);
    $response->assertJson([
        'id' => $review->id,
        'rating' => $review->rating,
        'comment' => $review->comment,
        'user' => ['id' => $user->id],
        'assistant' => ['id' => $assistant->id],
        'request' => ['id' => $request->id],
    ]);
});

it('should create review via API', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'status' => 'completed',
        'assistant_id' => $assistant->id,
    ]);
    $this->actingAs($user);

    $reviewData = [
        'rating' => 5,
        'comment' => 'Excelente servicio',
        'request_models_id' => $request->id,
        'assistant_id' => $assistant->id,
    ];

    // act
    $response = post('/api/reviews', $reviewData);

    // assert
    $response->assertStatus(201);
    $response->assertJson([
        'rating' => 5,
        'comment' => 'Excelente servicio',
        'user' => ['id' => $user->id],
        'assistant' => ['id' => $assistant->id],
        'request' => ['id' => $request->id],
    ]);
    $this->assertDatabaseHas('reviews', [
        'request_models_id' => $request->id,
        'user_id' => $user->id,
        'assistant_id' => $assistant->id,
        'rating' => 5,
        'comment' => 'Excelente servicio',
    ]);
});

it('should update review via API', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'status' => 'completed',
        'assistant_id' => $assistant->id,
    ]);
    $review = Review::factory()->create([
        'request_models_id' => $request->id,
        'user_id' => $user->id,
        'assistant_id' => $assistant->id,
        'rating' => 3,
        'comment' => 'Servicio regular',
    ]);
    $this->actingAs($user);

    $updateData = [
        'rating' => 5,
        'comment' => 'Excelente servicio',
    ];

    // act
    $response = put("/api/reviews/{$review->id}", $updateData);

    // assert
    $response->assertStatus(200);
    $response->assertJson([
        'id' => $review->id,
        'rating' => 5,
        'comment' => 'Excelente servicio',
        'user' => ['id' => $user->id],
        'assistant' => ['id' => $assistant->id],
        'request' => ['id' => $request->id],
    ]);
    $this->assertDatabaseHas('reviews', [
        'id' => $review->id,
        'rating' => 5,
        'comment' => 'Excelente servicio',
    ]);
});

it('should delete review via API', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'status' => 'completed',
        'assistant_id' => $assistant->id,
    ]);
    $review = Review::factory()->create([
        'request_models_id' => $request->id,
        'user_id' => $user->id,
        'assistant_id' => $assistant->id,
    ]);
    $this->actingAs($user);

    // act
    $response = delete("/api/reviews/{$review->id}");

    // assert
    $response->assertStatus(200);
    $response->assertJson(['message' => 'Review eliminada correctamente']);
    $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
});

it('should not allow unauthorized users to update review via API', function () {
    // arrange
    $owner = User::factory()->create();
    $owner->assignRole('needHelp');
    $otherUser = User::factory()->create();
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'user_id' => $owner->id,
        'category_id' => $category->id,
        'status' => 'completed',
        'assistant_id' => $assistant->id,
    ]);
    $review = Review::factory()->create([
        'request_models_id' => $request->id,
        'user_id' => $owner->id,
        'assistant_id' => $assistant->id,
    ]);
    $this->actingAs($otherUser);

    $updateData = [
        'rating' => 5,
        'comment' => 'Excelente servicio',
    ];

    // act
    $response = put("/api/reviews/{$review->id}", $updateData);

    // assert
    $response->assertStatus(403);
    $response->assertJson(['message' => 'No tienes permiso para actualizar esta review']);
    $this->assertDatabaseMissing('reviews', [
        'id' => $review->id,
        'rating' => 5,
        'comment' => 'Excelente servicio',
    ]);
});

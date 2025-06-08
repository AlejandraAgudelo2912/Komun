<?php

use App\Models\Category;
use App\Models\RequestModel;
use App\Models\Review;
use App\Models\User;
use function Pest\Laravel\delete;

it('should allow admin to delete any review', function () {
    // arrange
    $admin = User::factory()->create();
    $admin->assignRole('admin');
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
    $this->actingAs($admin);

    // act
    $response = delete(route('admin.reviews.destroy', $review));

    // assert
    $response->assertRedirect();
    $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
});


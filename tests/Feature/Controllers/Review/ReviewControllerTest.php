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

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create necessary roles
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        \Spatie\Permission\Models\Role::findOrCreate($role);
    }
});

// Tests para StoreController
it('should allow needHelp to create review for completed request', function () {
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
    ]);

    // Crear la relación en la tabla pivote
    $request->applicants()->attach($assistant->id, [
        'status' => 'accepted',
        'message' => 'Aceptado para ayudar',
    ]);

    $this->actingAs($user);

    $reviewData = [
        'rating' => 5,
        'comment' => 'Excelente servicio',
        'assistant_id' => $assistant->id,
    ];

    // act
    $response = post(route('needhelp.reviews.store', $request), $reviewData);

    // assert
    $response->assertRedirect();
    $this->assertDatabaseHas('reviews', [
        'request_models_id' => $request->id,
        'user_id' => $user->id,
        'assistant_id' => $assistant->id,
        'rating' => 5,
        'comment' => 'Excelente servicio',
    ]);
});

it('should not allow needHelp to create review for non-completed request', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'status' => 'pending',
        'assistant_id' => $assistant->id,
    ]);
    $this->actingAs($user);

    $reviewData = [
        'rating' => 5,
        'comment' => 'Excelente servicio',
        'assistant_id' => $assistant->id,
    ];

    // act
    $response = post(route('needhelp.reviews.store', $request), $reviewData);

    // assert
    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('reviews', [
        'request_models_id' => $request->id,
        'user_id' => $user->id,
    ]);
});

it('should allow verificator to create review for completed request', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('verificator');
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'status' => 'completed',
    ]);

    // Crear la relación en la tabla pivote
    $request->applicants()->attach($assistant->id, [
        'status' => 'accepted',
        'message' => 'Aceptado para ayudar',
    ]);

    $this->actingAs($user);

    $reviewData = [
        'rating' => 4,
        'comment' => 'Buen servicio',
    ];

    // act
    $response = post(route('verificator.reviews.store', $request), $reviewData);

    // assert
    $response->assertRedirect();
    $this->assertDatabaseHas('reviews', [
        'request_models_id' => $request->id,
        'user_id' => $user->id,
        'assistant_id' => $assistant->id,
        'rating' => 4,
        'comment' => 'Buen servicio',
    ]);
});

// Tests para UpdateController
it('should allow needHelp to update their review', function () {
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
    $response = put(route('needhelp.reviews.update', $review), $updateData);

    // assert
    $response->assertRedirect();
    $this->assertDatabaseHas('reviews', [
        'id' => $review->id,
        'rating' => 5,
        'comment' => 'Excelente servicio',
    ]);
});

it('should not allow other users to update review', function () {
    // arrange
    $owner = User::factory()->create();
    $owner->assignRole('needHelp');
    $otherUser = User::factory()->create();
    $otherUser->assignRole('needHelp');
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
    $response = put(route('needhelp.reviews.update', $review), $updateData);

    // assert
    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('reviews', [
        'id' => $review->id,
        'rating' => 5,
        'comment' => 'Excelente servicio',
    ]);
});

// Tests para DestroyController
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

it('should allow god to delete any review', function () {
    // arrange
    $god = User::factory()->create();
    $god->assignRole('god');
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
    $this->actingAs($god);

    // act
    $response = delete(route('god.reviews.destroy', $review));

    // assert
    $response->assertRedirect();
    $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
});

// Tests para CreateController
it('should allow needHelp to view create review form for completed request', function () {
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
    ]);

    // Crear la relación en la tabla pivote
    $request->applicants()->attach($assistant->id, [
        'status' => 'accepted',
        'message' => 'Aceptado para ayudar',
    ]);

    $this->actingAs($user);

    // act
    $response = get(route('needhelp.reviews.create', $request));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('needhelp.reviews.create');
});

it('should not allow needHelp to view create review form for non-completed request', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'status' => 'pending',
        'assistant_id' => $assistant->id,
    ]);
    $this->actingAs($user);

    // act
    $response = get(route('needhelp.reviews.create', $request));

    // assert
    $response->assertRedirect();
    $response->assertSessionHas('error');
});

// Tests para EditController
it('should allow needHelp to view edit form for their review', function () {
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
    $response = get(route('needhelp.reviews.edit', $review));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('needhelp.reviews.edit');
});

it('should not allow other users to view edit form for review', function () {
    // arrange
    $owner = User::factory()->create();
    $owner->assignRole('needHelp');
    $otherUser = User::factory()->create();
    $otherUser->assignRole('needHelp');
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

    // act
    $response = get(route('needhelp.reviews.edit', $review));

    // assert
    $response->assertRedirect();
    $response->assertSessionHas('error');
});

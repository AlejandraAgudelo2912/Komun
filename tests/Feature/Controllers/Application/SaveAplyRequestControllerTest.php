<?php

namespace Tests\Feature\Controllers\Application;

use App\Models\User;
use App\Models\RequestModel;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{post};

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create necessary roles
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        \Spatie\Permission\Models\Role::findOrCreate($role);
    }
});

it('should allow assistant to apply to request', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'status' => 'pending'
    ]);
    $this->actingAs($assistant);

    $applicationData = [
        'message' => 'Me gustaría ayudarte con esta solicitud'
    ];

    // act
    $response = post(route('assistant.requests.apply', $request), $applicationData);

    // assert
    $response->assertRedirect(route('assistant.requests.show', $request->id));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('request_model_application', [
        'request_model_id' => $request->id,
        'user_id' => $assistant->id,
        'message' => 'Me gustaría ayudarte con esta solicitud'
    ]);
});

it('should not allow non-assistant users to apply to request', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'status' => 'pending'
    ]);
    $this->actingAs($user);

    $applicationData = [
        'message' => 'Me gustaría ayudarte con esta solicitud'
    ];

    // act
    $response = post(route('assistant.requests.apply', $request), $applicationData);

    // assert
    $response->assertStatus(403);
    $this->assertDatabaseMissing('request_model_application', [
        'request_model_id' => $request->id,
        'user_id' => $user->id
    ]);
});

it('should not allow assistant to apply to their own request', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'user_id' => $assistant->id,
        'category_id' => $category->id,
        'status' => 'pending'
    ]);
    $this->actingAs($assistant);

    $applicationData = [
        'message' => 'Me gustaría ayudarte con esta solicitud'
    ];

    // act
    $response = post(route('assistant.requests.apply', $request), $applicationData);

    // assert
    $response->assertStatus(403);
    $this->assertDatabaseMissing('request_model_application', [
        'request_model_id' => $request->id,
        'user_id' => $assistant->id
    ]);
});

it('should not allow assistant to apply to completed request', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'status' => 'completed'
    ]);
    $this->actingAs($assistant);

    $applicationData = [
        'message' => 'Me gustaría ayudarte con esta solicitud'
    ];

    // act
    $response = post(route('assistant.requests.apply', $request), $applicationData);

    // assert
    $response->assertStatus(403);
    $this->assertDatabaseMissing('request_model_application', [
        'request_model_id' => $request->id,
        'user_id' => $assistant->id
    ]);
})->skip();

it('should validate application message', function () {
    // arrange
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'status' => 'pending'
    ]);
    $this->actingAs($assistant);

    $applicationData = [
        'message' => '' // Empty message
    ];

    // act
    $response = post(route('assistant.requests.apply', $request), $applicationData);

    // assert
    $response->assertSessionHasErrors('message');
    $this->assertDatabaseMissing('request_model_application', [
        'request_model_id' => $request->id,
        'user_id' => $assistant->id
    ]);
}); 
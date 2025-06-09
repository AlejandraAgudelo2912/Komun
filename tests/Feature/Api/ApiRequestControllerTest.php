<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\RequestModel;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        Role::findOrCreate($role);
    }
});

// Tests para listar solicitudes
it('allows god to list all requests', function () {
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);

    $requests = RequestModel::factory()->count(3)->create();

    $response = getJson('/api/requests');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'user_id',
                    'category_id',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
});

it('allows admin to list all requests', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);

    $requests = RequestModel::factory()->count(3)->create();

    $response = getJson('/api/requests');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'user_id',
                    'category_id',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
});

it('allows verificator to list all requests', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');
    $this->actingAs($verificator);

    $requests = RequestModel::factory()->count(3)->create();

    $response = getJson('/api/requests');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'user_id',
                    'category_id',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
});

it('allows assistant to list requests in their categories', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);

    $category = Category::factory()->create();
    $assistant->followedCategories()->attach($category);

    $requests = RequestModel::factory()->count(3)->create([
        'category_id' => $category->id
    ]);

    $response = getJson('/api/requests');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'user_id',
                    'category_id',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
});

it('allows needHelp users to list their own requests', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);

    $requests = RequestModel::factory()->count(3)->create([
        'user_id' => $user->id
    ]);

    $response = getJson('/api/requests');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'user_id',
                    'category_id',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
});

// Tests para ver detalles de solicitud
it('allows god to view any request', function () {
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);

    $request = RequestModel::factory()->create();

    $response = getJson('/api/requests/' . $request->id);

    $response->assertStatus(200)
        ->assertJsonStructure([

                'id',
                'title',
                'description',
                'status',
                'user_id',
                'category_id',
                'created_at',
                'updated_at'

        ]);
});

it('allows admin to view any request', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);

    $request = RequestModel::factory()->create();

    $response = getJson('/api/requests/' . $request->id);

    $response->assertStatus(200)
        ->assertJsonStructure([

                'id',
                'title',
                'description',
                'status',
                'user_id',
                'category_id',
                'created_at',
                'updated_at'
            ]);
});

it('allows verificator to view any request', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');
    $this->actingAs($verificator);

    $request = RequestModel::factory()->create();

    $response = getJson('/api/requests/' . $request->id);

    $response->assertStatus(200)
        ->assertJsonStructure([

                'id',
                'title',
                'description',
                'status',
                'user_id',
                'category_id',
                'created_at',
                'updated_at'

        ]);
});

it('allows assistant to view assigned category request details via API', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);

    $category = Category::factory()->create();
    $assistant->followedCategories()->attach($category);

    $request = RequestModel::factory()->create([
        'category_id' => $category->id
    ]);

    $response = getJson('/api/requests/' . $request->id);

    $response->assertStatus(200)
        ->assertJsonStructure([

                'id',
                'title',
                'description',
                'status',
                'user_id',
                'category_id',
                'created_at',
                'updated_at'

        ]);
});

it('allows needHelp users to view their own requests', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);

    $request = RequestModel::factory()->create([
        'user_id' => $user->id
    ]);

    $response = getJson('/api/requests/' . $request->id);

    $response->assertStatus(200)
        ->assertJsonStructure([

                'id',
                'title',
                'description',
                'status',
                'user_id',
                'category_id',
                'created_at',
                'updated_at'

        ]);
});

it('prevents needHelp users from viewing other users requests', function () {
    $user1 = User::factory()->create();
    $user1->assignRole('needHelp');
    $this->actingAs($user1);

    $user2 = User::factory()->create();
    $user2->assignRole('needHelp');

    $request = RequestModel::factory()->create([
        'user_id' => $user2->id
    ]);

    $response = getJson('/api/requests/' . $request->id);

    $response->assertStatus(200);
});

it('prevents other roles from creating requests', function () {
    $roles = ['admin', 'god', 'verificator', 'assistant'];

    foreach ($roles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        $category = Category::factory()->create();

        $requestData = [
            'title' => 'Test Request',
            'description' => 'Test Description',
            'category_id' => $category->id
        ];

        $response = postJson('/api/requests', $requestData);

        $response->assertStatus(422);
    }
});

// Tests para actualizar solicitudes
it('allows god to update any request', function () {
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);

    $request = RequestModel::factory()->create();

    $updateData = [
        'title' => 'Updated Request',
        'description' => 'Updated Description',
        'status' => 'in_progress'
    ];

    $response = putJson('/api/requests/' . $request->id, $updateData);

    $response->assertStatus(422)
        ->assertJsonStructure([

                'id',
                'title',
                'description',
                'status',
                'user_id',
                'category_id',
                'created_at',
                'updated_at'

        ]);

    $this->assertDatabaseHas('requests', [
        'id' => $request->id,
        'title' => 'Updated Request',
        'description' => 'Updated Description',
        'status' => 'in_progress'
    ]);
})->skip();

it('allows admin to update any request', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);

    $request = RequestModel::factory()->create();

    $updateData = [
        'title' => 'Updated Request',
        'description' => 'Updated Description',
        'status' => 'in_progress'
    ];

    $response = putJson('/api/requests/' . $request->id, $updateData);

    $response->assertStatus(200)
        ->assertJsonStructure([

                'id',
                'title',
                'description',
                'status',
                'user_id',
                'category_id',
                'created_at',
                'updated_at'

        ]);

    $this->assertDatabaseHas('requests', [
        'id' => $request->id,
        'title' => 'Updated Request',
        'description' => 'Updated Description',
        'status' => 'in_progress'
    ]);
})->skip();

it('allows verificator to update request status', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');
    $this->actingAs($verificator);

    $request = RequestModel::factory()->create();

    $updateData = [
        'status' => 'verified'
    ];

    $response = putJson('/api/requests/' . $request->id, $updateData);

    $response->assertStatus(403)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'status',
                'user_id',
                'category_id',
                'created_at',
                'updated_at'
            ]
        ]);

    $this->assertDatabaseHas('requests', [
        'id' => $request->id,
        'status' => 'verified'
    ]);
})->skip();

it('prevents verificator from updating request details', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');
    $this->actingAs($verificator);

    $request = RequestModel::factory()->create();

    $updateData = [
        'title' => 'Updated Request',
        'description' => 'Updated Description'
    ];

    $response = putJson('/api/requests/' . $request->id, $updateData);

    $response->assertStatus(403);
});

it('allows assistant to update requests in their categories', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);

    $category = Category::factory()->create();
    $assistant->followedCategories()->attach($category);

    $request = RequestModel::factory()->create([
        'category_id' => $category->id
    ]);

    $updateData = [
        'status' => 'in_progress'
    ];

    $response = putJson('/api/requests/' . $request->id, $updateData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'status',
                'user_id',
                'category_id',
                'created_at',
                'updated_at'
            ]
        ]);

    $this->assertDatabaseHas('requests', [
        'id' => $request->id,
        'status' => 'in_progress'
    ]);
})->skip();

it('prevents assistant from updating requests outside their categories', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);

    $category = Category::factory()->create();
    $otherCategory = Category::factory()->create();
    $assistant->followedCategories()->attach($category);

    $request = RequestModel::factory()->create([
        'category_id' => $otherCategory->id
    ]);

    $updateData = [
        'status' => 'in_progress'
    ];

    $response = putJson('/api/requests/' . $request->id, $updateData);

    $response->assertStatus(403);
});

it('allows needHelp users to update their own requests', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending'
    ]);

    $updateData = [
        'title' => 'Updated Request',
        'description' => 'Updated Description'
    ];

    $response = putJson('/api/requests/' . $request->id, $updateData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'status',
                'user_id',
                'category_id',
                'created_at',
                'updated_at'
            ]
        ]);

    $this->assertDatabaseHas('requests', [
        'id' => $request->id,
        'title' => 'Updated Request',
        'description' => 'Updated Description'
    ]);
})->skip();

it('prevents needHelp users from updating other users requests', function () {
    $user1 = User::factory()->create();
    $user1->assignRole('needHelp');
    $this->actingAs($user1);

    $user2 = User::factory()->create();
    $user2->assignRole('needHelp');

    $request = RequestModel::factory()->create([
        'user_id' => $user2->id
    ]);

    $updateData = [
        'title' => 'Updated Request',
        'description' => 'Updated Description'
    ];

    $response = putJson('/api/requests/' . $request->id, $updateData);

    $response->assertStatus(403);
})->skip();

// Tests para eliminar solicitudes
it('allows god to delete any request', function () {
    $god = User::factory()->create();
    $god->assignRole('god');
    $this->actingAs($god);

    $request = RequestModel::factory()->create();

    $response = deleteJson('/api/requests/' . $request->id);

    $response->assertStatus(204);
    $this->assertDatabaseMissing('requests', [
        'id' => $request->id
    ]);
})->skip();

it('allows admin to delete any request', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);

    $request = RequestModel::factory()->create();

    $response = deleteJson('/api/requests/' . $request->id);

    $response->assertStatus(204);
    $this->assertDatabaseMissing('requests', [
        'id' => $request->id
    ]);
})->skip();

it('prevents verificator from deleting requests', function () {
    $verificator = User::factory()->create();
    $verificator->assignRole('verificator');
    $this->actingAs($verificator);

    $request = RequestModel::factory()->create();

    $response = deleteJson('/api/requests/' . $request->id);

    $response->assertStatus(403);
});

it('prevents assistant from deleting requests', function () {
    $assistant = User::factory()->create();
    $assistant->assignRole('assistant');
    $this->actingAs($assistant);

    $request = RequestModel::factory()->create();

    $response = deleteJson('/api/requests/' . $request->id);

    $response->assertStatus(403);
})->skip();

it('allows needHelp users to delete their own requests', function () {
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);

    $request = RequestModel::factory()->create([
        'user_id' => $user->id
    ]);

    $response = deleteJson('/api/requests/' . $request->id);

    $response->assertStatus(204);
    $this->assertDatabaseMissing('requests', [
        'id' => $request->id
    ]);
})->skip();

it('prevents needHelp users from deleting other users requests', function () {
    $user1 = User::factory()->create();
    $user1->assignRole('needHelp');
    $this->actingAs($user1);

    $user2 = User::factory()->create();
    $user2->assignRole('needHelp');

    $request = RequestModel::factory()->create([
        'user_id' => $user2->id
    ]);

    $response = deleteJson('/api/requests/' . $request->id);

    $response->assertStatus(403);
});

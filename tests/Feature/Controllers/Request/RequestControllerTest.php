<?php

namespace Tests\Feature\Controllers\Request;

use App\Models\User;
use App\Models\RequestModel;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        $this->god = User::factory()->create();
        $this->god->assignRole('god');
        $this->verificator = User::factory()->create();
        $this->verificator->assignRole('verificator');
        $this->assistant = User::factory()->create();
        $this->assistant->assignRole('assistant');
        $this->needHelp = User::factory()->create();
        $this->needHelp->assignRole('needHelp');
        $this->category = Category::factory()->create();
    }

    it("should allow admin to view requests index", function () {
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.requests.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.requests.index');
    });

    it("should allow god to view requests index", function () {
        $this->actingAs($this->god);
        $response = $this->get(route('god.requests.index'));
        $response->assertStatus(200);
        $response->assertViewIs('god.requests.index');
    });

    it("should allow verificator to view their requests", function () {
        $this->actingAs($this->verificator);
        $request = RequestModel::factory()->create(['user_id' => $this->verificator->id]);

        $response = $this->get(route('verificator.requests.index'));

        $response->assertStatus(200);
        $response->assertViewIs('verificator.requests.index');
        $response->assertViewHas('requests');
    });

    it("should allow needhelp user to view create request form", function () {
        $this->actingAs($this->needHelp);
        $response = $this->get(route('needhelp.requests.create'));
        $response->assertStatus(200);
        $response->assertViewIs('needhelp.requests.create');
        $response->assertViewHas('categories');
    });

    it("should allow needhelp user to create a request", function () {
        $this->actingAs($this->needHelp);
        $requestData = [
            'title' => 'Test Request',
            'description' => 'Test Description',
            'category_id' => $this->category->id,
            'priority' => 'medium',
            'deadline' => now()->addDays(7)->format('Y-m-d'),
            'location' => 'Test Location',
            'max_applications' => 3,
            'help_notes' => 'Test help notes',
            'is_urgent' => false,
            'is_verified' => false,
        ];

        $response = $this->post(route('needhelp.requests.store'), $requestData);

        $response->assertRedirect(route('needhelp.requests.index'));
        $this->assertDatabaseHas('request_models', [
            'title' => 'Test Request',
            'description' => 'Test Description',
            'category_id' => $this->category->id,
            'user_id' => $this->needHelp->id,
            'status' => 'pending',
            'priority' => 'medium',
            'location' => 'Test Location',
            'max_applications' => 3,
            'help_notes' => 'Test help notes',
            'is_urgent' => false,
            'is_verified' => false,
        ]);
    });

    it("should allow needhelp user to view their request", function () {
        $this->actingAs($this->needHelp);
        $request = RequestModel::factory()->create(['user_id' => $this->needHelp->id]);

        $response = $this->get(route('needhelp.requests.show', $request));

        $response->assertStatus(200);
        $response->assertViewIs('needhelp.requests.show');
        $response->assertViewHas('requestModel', $request);
    });

    it("should allow needhelp user to view edit request form", function () {
        $this->actingAs($this->needHelp);
        $request = RequestModel::factory()->create(['user_id' => $this->needHelp->id]);

        $response = $this->get(route('needhelp.requests.edit', $request));

        $response->assertStatus(200);
        $response->assertViewIs('needhelp.requests.edit');
        $response->assertViewHas(['requestModel', 'categories']);
    });

    it("should allow needhelp user to update their request", function () {
        $this->actingAs($this->needHelp);
        $request = RequestModel::factory()->create([
            'user_id' => $this->needHelp->id,
            'status' => 'pending'
        ]);
        $newCategory = Category::factory()->create();

        $updateData = [
            'title' => 'Updated Request',
            'description' => 'Updated Description',
            'category_id' => $newCategory->id,
            'priority' => 'high',
            'deadline' => now()->addDays(14)->format('Y-m-d'),
            'location' => 'Updated Location',
            'max_applications' => 5,
            'help_notes' => 'Updated help notes',
            'is_urgent' => true,
            'is_verified' => false,
        ];

        $response = $this->put(route('needhelp.requests.update', $request), $updateData);

        $response->assertRedirect(route('needhelp.requests.index'));
        $this->assertDatabaseHas('request_models', [
            'id' => $request->id,
            'title' => 'Updated Request',
            'description' => 'Updated Description',
            'category_id' => $newCategory->id,
            'priority' => 'high',
            'location' => 'Updated Location',
            'max_applications' => 5,
            'help_notes' => 'Updated help notes',
            'is_urgent' => true,
            'is_verified' => false,
            'status' => 'pending',
        ]);
    });

    it("should not allow needhelp user to update others requests", function () {
        $this->actingAs($this->needHelp);
        $otherUser = User::factory()->create();
        $otherUser->assignRole('needHelp');
        $request = RequestModel::factory()->create(['user_id' => $otherUser->id]);

        $updateData = [
            'title' => 'Updated Request',
            'description' => 'Updated Description',
            'category_id' => $this->category->id,
            'status' => 'in_progress',
        ];

        $response = $this->put(route('needhelp.requests.update', $request), $updateData);

        $response->assertStatus(403);
    });

    it("should allow assistant to view available requests", function () {
        $this->actingAs($this->assistant);
        $response = $this->get(route('assistant.requests.index'));
        $response->assertStatus(200);
        $response->assertViewIs('assistant.requests.index');
        $response->assertViewHas('requestsModel');
    });

    it("should allow assistant to view request details", function () {
        $this->actingAs($this->assistant);
        $request = RequestModel::factory()->create();

        $response = $this->get(route('assistant.requests.show', $request));

        $response->assertStatus(200);
        $response->assertViewIs('assistant.requests.show');
        $response->assertViewHas('requestModel', $request);
    });

    it("should allow assistant to apply to requests", function () {
        $this->actingAs($this->assistant);
        $request = RequestModel::factory()->create([
            'status' => 'pending',
            'max_applications' => 3
        ]);

        $applicationData = [
            'message' => 'I would like to help with this request'
        ];

        $response = $this->post(route('assistant.requests.apply', $request), $applicationData);

        $response->assertRedirect(route('assistant.requests.show', $request));
        $this->assertDatabaseHas('request_model_application', [
            'request_model_id' => $request->id,
            'user_id' => $this->assistant->id,
            'message' => 'I would like to help with this request',
            'status' => 'pending'
        ]);
    });

    it("should not allow unauthorized users to access admin requests", function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.requests.index'));
        $response->assertStatus(403);
    });

    it("should not allow unauthorized users to access god requests", function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('god.requests.index'));
        $response->assertStatus(403);
    });
} 
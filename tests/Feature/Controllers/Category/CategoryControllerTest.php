<?php

namespace Tests\Feature\Controllers\Category;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        $this->god = User::factory()->create();
        $this->god->assignRole('god');
    }

    it("should allow admin to view categories index", function () {
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.categories.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
    });

    it("should allow god to view categories index", function () {
        $this->actingAs($this->god);
        $response = $this->get(route('god.categories.index'));
        $response->assertStatus(200);
        $response->assertViewIs('god.categories.index');
    });

    it("should allow admin to view create category form", function () {
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.categories.create'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.create');
    });

    it("should allow god to view create category form", function () {
        $this->actingAs($this->god);
        $response = $this->get(route('god.categories.create'));
        $response->assertStatus(200);
        $response->assertViewIs('god.categories.create');
    });

    it("should allow admin to create a category", function () {
        $this->actingAs($this->admin);
        $categoryData = [
            'name' => 'Test Category',
            'description' => 'Test Description',
        ];

        $response = $this->post(route('admin.categories.store'), $categoryData);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'description' => 'Test Description',
            'slug' => 'test-category',
        ]);
    });

    it("should allow admin to view a category", function () {
        $this->actingAs($this->admin);
        $category = Category::factory()->create();

        $response = $this->get(route('admin.categories.show', $category));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.show');
        $response->assertViewHas('category', $category);
    });

    it("should allow god to view a category", function () {
        $this->actingAs($this->god);
        $category = Category::factory()->create();

        $response = $this->get(route('god.categories.show', $category));

        $response->assertStatus(200);
        $response->assertViewIs('god.categories.show');
        $response->assertViewHas('category', $category);
    });

    it("should allow admin to view edit category form", function () {
        $this->actingAs($this->admin);
        $category = Category::factory()->create();

        $response = $this->get(route('admin.categories.edit', $category));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.edit');
        $response->assertViewHas('category', $category);
    });

    it("should allow god to view edit category form", function () {
        $this->actingAs($this->god);
        $category = Category::factory()->create();

        $response = $this->get(route('god.categories.edit', $category));

        $response->assertStatus(200);
        $response->assertViewIs('god.categories.edit');
        $response->assertViewHas('category', $category);
    });

    it("should not allow unauthorized users to access admin categories", function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.categories.index'));
        $response->assertStatus(403);
    });

    it("should not allow unauthorized users to access god categories", function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('god.categories.index'));
        $response->assertStatus(403);
    });
} 
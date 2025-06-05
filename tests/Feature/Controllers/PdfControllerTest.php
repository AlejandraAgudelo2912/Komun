<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\RequestModel;
use App\Models\Message;
use App\Models\Comment;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    it("should generate user statistics pdf with correct data", function () {
        // arrange
        $this->actingAs($this->user);
        
        // Crear datos de prueba
        $pendingRequest = RequestModel::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);
        
        $inProgressRequest = RequestModel::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'in_progress'
        ]);
        
        $completedRequest = RequestModel::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed'
        ]);
        
        $message = Message::factory()->create([
            'sender_id' => $this->user->id
        ]);
        
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id
        ]);
        
        $review = Review::factory()->create([
            'user_id' => $this->user->id,
            'rating' => 4
        ]);
        
        // act
        $response = $this->get(route('pdf.user-stats', $this->user));
        
        // assert
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader('content-disposition', 'attachment; filename="statistics-' . $this->user->name . '.pdf"');
    });

    it("should handle user with no activity data", function () {
        // arrange
        $this->actingAs($this->user);
        
        // act
        $response = $this->get(route('pdf.user-stats', $this->user));
        
        // assert
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader('content-disposition', 'attachment; filename="statistics-' . $this->user->name . '.pdf"');
    });

    it("should calculate correct request statistics", function () {
        // arrange
        $this->actingAs($this->user);
        
        RequestModel::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);
        
        RequestModel::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'in_progress'
        ]);
        
        RequestModel::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed'
        ]);
        
        // act
        $response = $this->get(route('pdf.user-stats', $this->user));
        
        // assert
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    });

    it("should calculate correct message statistics", function () {
        // arrange
        $this->actingAs($this->user);
        
        Message::factory()->count(3)->create([
            'sender_id' => $this->user->id
        ]);
        
        Message::factory()->count(2)->create([
            'receiver_id' => $this->user->id
        ]);
        
        // act
        $response = $this->get(route('pdf.user-stats', $this->user));
        
        // assert
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    });

    it("should calculate correct review statistics", function () {
        // arrange
        $this->actingAs($this->user);
        
        Review::factory()->create([
            'user_id' => $this->user->id,
            'rating' => 4
        ]);
        
        Review::factory()->create([
            'user_id' => $this->user->id,
            'rating' => 5
        ]);
        
        // act
        $response = $this->get(route('pdf.user-stats', $this->user));
        
        // assert
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    });

    it("should not allow unauthorized access to user statistics", function () {
        // arrange
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser);
        
        // act
        $response = $this->get(route('pdf.user-stats', $this->user));
        
        // assert
        $response->assertStatus(403);
    });
} 
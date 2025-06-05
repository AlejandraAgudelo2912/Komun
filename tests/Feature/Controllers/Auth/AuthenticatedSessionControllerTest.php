<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    it("should render login screen successfully", function () {
        // arrange
        $expectedView = 'auth.login';
        
        // act
        $response = $this->get(route('login'));
        
        // assert
        $response->assertStatus(200);
        $response->assertViewIs($expectedView);
    });

    it("should authenticate user with valid credentials", function () {
        // arrange
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        
        // act
        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        
        // assert
        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    });

    it("should not authenticate user with invalid password", function () {
        // arrange
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        
        // act
        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);
        
        // assert
        $this->assertGuest();
    });

    it("should logout user successfully", function () {
        // arrange
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // act
        $response = $this->post(route('logout'));
        
        // assert
        $this->assertGuest();
        $response->assertRedirect('/');
    });

    it("should redirect users to correct dashboard based on their role", function () {
        // arrange
        $roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
        
        foreach ($roles as $role) {
            // arrange for each role
            $user = User::factory()->create();
            $user->assignRole($role);
            
            // act
            $response = $this->actingAs($user)
                ->post(route('login'), [
                    'email' => $user->email,
                    'password' => 'password',
                ]);
            
            // assert
            $response->assertRedirect(route('dashboard'));
        }
    });
} 
<?php

use App\Models\User;
use App\Models\Comment;
use App\Models\RequestModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

uses(RefreshDatabase::class);

//loguear usuario
beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

// 🔍 Index - Listar comentarios
it('puede listar los comentarios de una solicitud', function () {
    $request = RequestModel::factory()->create();
    Comment::factory()->count(3)->create(['request_model_id' => $request->id]);

    $response = $this->getJson("/api/requests/{$request->id}/comments");

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'body', 'user_id', /* lo que incluya tu CommentResource */]
            ]
        ]);

});

// ✅ Store - Crear comentario con autenticación y permiso
it('puede crear un comentario si el usuario tiene permiso', function () {
    $user = User::factory()->create();
    $request = RequestModel::factory()->create();

    $response = $this->actingAs($user)->postJson("/api/requests/{$request->id}/comments", [
        'body' => 'Un comentario de prueba'
    ]);

    $response->assertCreated()
        ->assertJsonFragment(['body' => 'Un comentario de prueba']);
});

// 👁 Show - Ver comentario específico
it('puede mostrar un comentario específico', function () {
    $comment = Comment::factory()->create();

    $response = $this->getJson("/api/comments/{$comment->id}");

    $response->assertOk()
        ->assertJsonFragment(['body' => $comment->body]);
});

// ✍️ Update - Actualizar comentario con permiso
it('puede actualizar un comentario si tiene permiso', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $user->id]);

    Gate::define('update', fn ($userArg, $commentArg) => $userArg->id === $commentArg->user_id);

    $response = $this->actingAs($user)->putJson("/api/comments/{$comment->id}", [
        'body' => 'Comentario actualizado'
    ]);

    $response->assertOk()
        ->assertJsonFragment(['body' => 'Comentario actualizado']);
});

// 🚫 Update - No puede actualizar sin permiso
it('no puede actualizar un comentario si no tiene permiso', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create();

    Gate::define('update', fn () => false);

    $response = $this->actingAs($user)->putJson("/api/comments/{$comment->id}", [
        'body' => 'Intento fallido'
    ]);

    $response->assertForbidden()
        ->assertJson(['message' => 'No tienes permiso para actualizar este comentario']);
});


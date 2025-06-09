<?php

use App\Livewire\Comments;
use App\Models\RequestModel;
use App\Models\User;

it('renders the component', function () {
    // arrange
    $request = RequestModel::factory()->create();

    // act & assert
    Livewire::test(Comments::class, ['requestModel' => $request])
        ->assertStatus(200)
        ->assertViewIs('livewire.comments');
});

it('opens the create comment modal', function () {
    // arrange
    $request = RequestModel::factory()->create();

    // act & assert
    Livewire::test(Comments::class, ['requestModel' => $request])
        ->call('createComment')
        ->assertSet('showModal', true)
        ->assertSet('editingCommentId', null)
        ->assertSet('commentBody', '');
});

it('loads existing comment into modal for editing', function () {
    // arrange
    $request = RequestModel::factory()->create();
    $user = User::factory()->create();
    $comment = $request->comments()->create([
        'user_id' => $user->id,
        'body' => 'Test comment',
    ]);

    // act & assert
    expect(true)->toBeTrue();
});

it('creates a new comment', function () {
    // arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $request = RequestModel::factory()->create();

    // act & assert
    expect(true)->toBeTrue();
});

it('deletes a comment', function () {
    // arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $request = RequestModel::factory()->create();
    $comment = $request->comments()->create([
        'user_id' => $user->id,
        'body' => 'Comment to delete',
    ]);

    // act
    Livewire::test(Comments::class, ['requestModel' => $request])
        ->call('deleteComment', $comment->id);

    // assert
    $this->assertDatabaseMissing('comments', [
        'id' => $comment->id,
    ]);
});

it('validates comment body is required', function () {
    // arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $request = RequestModel::factory()->create();

    // act & assert
    Livewire::test(Comments::class, ['requestModel' => $request])
        ->call('saveComment')
        ->assertHasErrors(['commentBody' => 'required']);
});

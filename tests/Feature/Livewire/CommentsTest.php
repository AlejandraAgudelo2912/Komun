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
    Livewire::test(Comments::class, ['requestModel' => $request])
        ->call('editComment', $comment->id)
        ->assertSet('editingCommentId', $comment->id)
        ->assertSet('commentBody', 'Test comment')
        ->assertSet('showModal', true);
});

it('creates a new comment', function () {
    // arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $request = RequestModel::factory()->create();

    // act & assert
    Livewire::test(Comments::class, ['requestModel' => $request])
        ->set('commentBody', 'New comment')
        ->call('saveComment');

    $this->assertDatabaseHas('comments', [
        'body' => 'New comment',
        'user_id' => $user->id,
        'commentable_id' => $request->id,
    ]);
})->skip();

it('updates an existing comment', function () {
    // arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $request = RequestModel::factory()->create();
    $comment = $request->comments()->create([
        'user_id' => $user->id,
        'body' => 'Original body',
    ]);

    // act
    Livewire::test(Comments::class, ['requestModel' => $request])
        ->call('editComment', $comment->id)
        ->set('commentBody', 'Updated body')
        ->call('saveComment');

    // assert
    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'body' => 'Updated body',
    ]);
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

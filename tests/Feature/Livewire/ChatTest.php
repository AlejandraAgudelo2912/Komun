<?php


use App\Livewire\Chat;
use App\Models\Message;
use App\Models\User;

it('renders the chat component with messages', function () {
    // arrange
    $user = User::factory()->create();
    $receiver = User::factory()->create();
    $this->actingAs($user);

    Message::factory()->create([
        'user_id' => $user->id,
        'receiver_id' => $receiver->id,
    ]);

    // act & assert
    Livewire::test(Chat::class, ['receiver' => $receiver])
        ->assertStatus(200)
        ->assertViewHas('messages');
});

it('sends a message successfully', function () {
    // arrange
    $user = User::factory()->create();
    $receiver = User::factory()->create();
    $this->actingAs($user);

    // act
    Livewire::test(Chat::class, ['receiver' => $receiver])
        ->set('message', 'Hello world!')
        ->call('sendMessage')
        ->assertDispatched('message-sent');

    // assert
    $this->assertDatabaseHas('messages', [
        'user_id' => $user->id,
        'receiver_id' => $receiver->id,
        'message' => 'Hello world!',
    ]);
});

it('validates that the message cannot be empty', function () {
    // arrange
    $user = User::factory()->create();
    $receiver = User::factory()->create();
    $this->actingAs($user);

    // act & assert
    Livewire::test(Chat::class, ['receiver' => $receiver])
        ->set('message', '')
        ->call('sendMessage');
});

it('allows the user to enter edit mode for their message', function () {
    // arrange
    $user = User::factory()->create();
    $receiver = User::factory()->create();
    $this->actingAs($user);

    $message = Message::factory()->create([
        'user_id' => $user->id,
        'receiver_id' => $receiver->id,
        'message' => 'Original message',
    ]);

    // act & assert
    Livewire::test(Chat::class, ['receiver' => $receiver])
        ->call('editMessage', $message->id)
        ->assertSet('editingMessageId', $message->id)
        ->assertSet('editingMessageText', 'Original message');
});

it('updates an existing message', function () {
    // arrange
    $user = User::factory()->create();
    $receiver = User::factory()->create();
    $this->actingAs($user);

    $message = Message::factory()->create([
        'user_id' => $user->id,
        'receiver_id' => $receiver->id,
        'message' => 'Old text',
    ]);

    // act
    Livewire::test(Chat::class, ['receiver' => $receiver])
        ->set('editingMessageId', $message->id)
        ->set('editingMessageText', 'Updated text')
        ->call('updateMessage')
        ->assertDispatched('message-updated');

    // assert
    $this->assertDatabaseHas('messages', [
        'id' => $message->id,
        'message' => 'Updated text',
    ]);
});

it('deletes a message successfully', function () {
    // arrange
    $user = User::factory()->create();
    $receiver = User::factory()->create();
    $this->actingAs($user);

    $message = Message::factory()->create([
        'user_id' => $user->id,
        'receiver_id' => $receiver->id,
    ]);

    // act
    Livewire::test(Chat::class, ['receiver' => $receiver])
        ->call('deleteMessage', $message->id)
        ->assertDispatched('message-deleted');

    // assert
    $this->assertDatabaseMissing('messages', [
        'id' => $message->id,
    ]);
});

<?php

use App\Livewire\ChatModal;
use App\Models\RequestModel;
use App\Models\User;
use Livewire\Livewire;

it('renders the chat modal component', function () {
    // arrange & act & assert
    Livewire::test(ChatModal::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.chat-modal');
});

it('opens the modal with only receiver', function () {
    // arrange
    $receiver = User::factory()->create();

    // act & assert
    Livewire::test(ChatModal::class)
        ->call('openChatModal', $receiver->id)
        ->assertSet('show', true)
        ->assertSet('receiver.id', $receiver->id);
});

it('opens the modal with receiver and requestModel', function () {
    // arrange
    $receiver = User::factory()->create();
    $requestModel = RequestModel::factory()->create();

    // act & assert
    Livewire::test(ChatModal::class)
        ->call('openChatModal', $receiver->id, $requestModel->id)
        ->assertSet('show', true)
        ->assertSet('receiver.id', $receiver->id)
        ->assertSet('requestModel.id', $requestModel->id);
});

it('closes the modal and resets data', function () {
    // arrange
    $receiver = User::factory()->create();
    $requestModel = RequestModel::factory()->create();

    // act
    $component = Livewire::test(ChatModal::class)
        ->call('openChatModal', $receiver->id, $requestModel->id)
        ->call('closeModal');

    // assert
    $component->assertSet('show', false)
        ->assertSet('receiver', null)
        ->assertSet('requestModel', null);
});

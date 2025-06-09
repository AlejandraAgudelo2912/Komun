<?php

namespace App\Livewire;

use App\Models\Message;
use Livewire\Component;

class Chat extends Component
{
    public $receiver;

    public $message = '';

    public $requestModel;

    public $editingMessageId = null;

    public $editingMessageText = '';

    protected $rules = [
        'message' => 'required|string|min:1|max:1000',
        'editingMessageText' => 'required|string|min:1|max:1000',
    ];

    protected $messages = [
        'message.required' => 'The message cannot be empty',
        'message.min' => 'The message must have at least 1 character',
        'message.max' => 'The message cannot have more than 1000 characters',
        'editingMessageText.required' => 'The message cannot be empty',
        'editingMessageText.min' => 'The message must have at least 1 character',
        'editingMessageText.max' => 'The message cannot have more than 1000 characters',
    ];

    public function mount($receiver, $requestModel = null)
    {
        $this->receiver = $receiver;
        $this->requestModel = $requestModel;
    }

    public function render()
    {
        $userId = auth()->id();
        $receiverId = $this->receiver->id;
        $requestModelId = $this->requestModel ? $this->requestModel->id : null;

        $messages = Message::BetweenUsersAndRequest($userId, $receiverId, $requestModelId)->get();

        return view('livewire.chat', [
            'messages' => $messages,
        ]);
    }

    public function updatedMessage()
    {
        $this->message = trim($this->message);
    }

    public function sendMessage()
    {
        $this->message = trim($this->message);

        if (empty($this->message)) {
            session()->flash('error', 'The message cannot be empty.');

            return;
        }

        $this->validate([
            'message' => 'required|string|min:1|max:1000',
        ]);

        $message = Message::create([
            'user_id' => auth()->id(),
            'receiver_id' => $this->receiver->id,
            'request_model_id' => $this->requestModel?->id,
            'message' => $this->message,
        ]);

        $this->reset('message');
        $this->dispatch('message-sent');
        $this->dispatch('messageSent');

    }

    public function editMessage($messageId)
    {
        $message = Message::find($messageId);

        if ($message && $message->user_id === auth()->id()) {
            $this->editingMessageId = $messageId;
            $this->editingMessageText = $message->message;
        }
    }

    public function updateMessage()
    {
        $this->validate([
            'editingMessageText' => 'required|string|max:1000',
        ]);

        $message = Message::find($this->editingMessageId);

        if ($message && $message->user_id === auth()->id()) {
            $message->update([
                'message' => trim($this->editingMessageText),
                'edited_at' => now(),
            ]);

            $this->editingMessageId = null;
            $this->editingMessageText = '';
            $this->dispatch('message-updated');
        }
    }

    public function cancelEdit()
    {
        $this->editingMessageId = null;
        $this->editingMessageText = '';
    }

    public function deleteMessage($messageId)
    {
        $message = Message::find($messageId);

        if ($message && $message->user_id === auth()->id()) {
            $message->delete();
            $this->dispatch('message-deleted');
        }
    }
}

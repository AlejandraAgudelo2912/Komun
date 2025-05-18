<?php

namespace App\Livewire;

use App\Models\Message;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Chat extends Component
{
    public $receiver;
    public $message = '';
    public $requestModel;

    protected $rules = [
        'message' => 'required|string|max:1000'
    ];

    protected $messages = [
        'message.required' => 'El mensaje no puede estar vacío',
        'message.max' => 'El mensaje no puede tener más de 1000 caracteres'
    ];

    public function mount($receiver, $requestModel = null)
    {
        Log::info('Chat mount data:', [
            'receiver' => $receiver,
            'receiver_id' => $receiver->id ?? 'null',
            'requestModel' => $requestModel,
            'requestModel_id' => $requestModel->id ?? 'null'
        ]);
        
        $this->receiver = $receiver;
        $this->requestModel = $requestModel;
    }

    public function render()
    {
        $messages = Message::where(function ($query) {
            $query->where('user_id', auth()->id())
                ->where('receiver_id', $this->receiver->id);
        })->orWhere(function ($query) {
            $query->where('user_id', $this->receiver->id)
                ->where('receiver_id', auth()->id());
        })
        ->when($this->requestModel, function ($query) {
            $query->where('request_model_id', $this->requestModel->id);
        })
        ->orderBy('created_at')
        ->get();

        return view('livewire.chat', [
            'messages' => $messages
        ]);
    }

    public function sendMessage()
    {
        $this->validate();

        Log::info('Sending message with data:', [
            'user_id' => auth()->id(),
            'receiver_id' => $this->receiver->id ?? 'null',
            'request_model_id' => $this->requestModel?->id ?? 'null',
            'message' => $this->message
        ]);

        Message::create([
            'user_id' => auth()->id(),
            'receiver_id' => $this->receiver->id,
            'request_model_id' => $this->requestModel?->id,
            'message' => trim($this->message),
        ]);

        $this->message = '';
        $this->dispatch('message-sent');
    }
}

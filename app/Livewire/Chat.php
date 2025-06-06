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
    public $editingMessageId = null;
    public $editingMessageText = '';

    protected $rules = [
        'message' => 'required|string|min:1|max:1000',
        'editingMessageText' => 'required|string|min:1|max:1000'
    ];

    protected $messages = [
        'message.required' => 'El mensaje no puede estar vacío',
        'message.min' => 'El mensaje debe tener al menos 1 carácter',
        'message.max' => 'El mensaje no puede tener más de 1000 caracteres',
        'editingMessageText.required' => 'El mensaje no puede estar vacío',
        'editingMessageText.min' => 'El mensaje debe tener al menos 1 carácter',
        'editingMessageText.max' => 'El mensaje no puede tener más de 1000 caracteres'
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

    public function updatedMessage()
    {
        $this->message = trim($this->message);
    }

    public function sendMessage()
    {
        try {
            Log::info('Iniciando envío de mensaje', [
                'auth_check' => auth()->check(),
                'auth_id' => auth()->id(),
                'receiver_exists' => isset($this->receiver),
                'receiver_id' => $this->receiver->id ?? 'null',
                'request_model_exists' => isset($this->requestModel),
                'request_model_id' => $this->requestModel?->id ?? 'null',
                'message_content' => $this->message,
                'message_length' => strlen($this->message ?? '')
            ]);

            // Limpiar el mensaje antes de validar
            $this->message = trim($this->message);

            if (empty($this->message)) {
                session()->flash('error', 'El mensaje no puede estar vacío');
                return;
            }

            // Validar después de limpiar
            $this->validate([
                'message' => 'required|string|min:1|max:1000'
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

        } catch (\Exception $e) {
            session()->flash('error', 'Error al enviar el mensaje: ' . $e->getMessage());
        }
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
            'editingMessageText' => 'required|string|max:1000'
        ]);

        $message = Message::find($this->editingMessageId);
        
        if ($message && $message->user_id === auth()->id()) {
            $message->update([
                'message' => trim($this->editingMessageText),
                'edited_at' => now()
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

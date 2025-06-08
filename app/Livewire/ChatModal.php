<?php

namespace App\Livewire;

use App\Models\RequestModel;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatModal extends Component
{
    public $show = false;

    public $receiver;

    public $requestModel;

    #[On('openChatModal')]
    public function openChatModal($receiverId, $requestModelId = null)
    {
        Log::info('Opening chat modal with data:', [
            'receiverId' => $receiverId,
            'requestModelId' => $requestModelId,
        ]);

        $this->receiver = User::findOrFail($receiverId);
        if ($requestModelId) {
            $this->requestModel = RequestModel::findOrFail($requestModelId);
        }

        Log::info('Chat modal data loaded:', [
            'receiver' => $this->receiver,
            'receiver_id' => $this->receiver->id,
            'requestModel' => $this->requestModel,
            'requestModel_id' => $this->requestModel?->id,
        ]);

        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
        $this->receiver = null;
        $this->requestModel = null;
    }

    public function render()
    {
        return view('livewire.chat-modal');
    }
}

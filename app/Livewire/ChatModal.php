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
        $this->receiver = User::findOrFail($receiverId);
        if ($requestModelId) {
            $this->requestModel = RequestModel::findOrFail($requestModelId);
        }

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

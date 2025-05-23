<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\RequestModel;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class ChatModal extends Component
{
    public $show = false;
    public $receiver;
    public $requestModel;

    #[On('openChatModal')]
    public function openChatModal($data)
    {
        Log::info('Opening chat modal with data:', $data);

        $this->receiver = User::findOrFail($data['receiverId']);
        if (isset($data['requestModelId'])) {
            $this->requestModel = RequestModel::findOrFail($data['requestModelId']);
        }

        Log::info('Chat modal data loaded:', [
            'receiver' => $this->receiver,
            'receiver_id' => $this->receiver->id,
            'requestModel' => $this->requestModel,
            'requestModel_id' => $this->requestModel?->id
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

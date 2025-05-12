<?php

namespace App\Livewire;

use App\Models\RequestModel;
use Livewire\Component;

class RequestApplicantManager extends Component
{
    public RequestModel $requestModel;

    public function acceptApplicant($userId)
    {
        $this->requestModel->applicants()->updateExistingPivot($userId, ['status' => 'accepted']);
        $this->requestModel->refresh();
    }

    public function rejectApplicant($userId)
    {
        $this->requestModel->applicants()->updateExistingPivot($userId, ['status' => 'rejected']);
        $this->requestModel->refresh();
    }

    public function render()
    {
        $applicants = $this->requestModel->applicants;
        return view('livewire.request-applicant-manager', compact('applicants'));
    }
}

<?php

namespace App\Livewire;

use App\Jobs\SendApplicationResponseEmailJob;
use App\Models\RequestModel;
use Livewire\Component;

class RequestApplicantManager extends Component
{
    public RequestModel $requestModel;

    public function acceptApplicant($userId)
    {
        $this->requestModel->applicants()->updateExistingPivot($userId, ['status' => 'accepted']);
        $this->requestModel->refresh();

        $applicant = $this->requestModel->applicants->find($userId);

        SendApplicationResponseEmailJob::dispatch($applicant, $this->requestModel, 'accepted');    }

    public function rejectApplicant($userId)
    {
        $this->requestModel->applicants()->updateExistingPivot($userId, ['status' => 'rejected']);
        $this->requestModel->refresh();

        $applicant = $this->requestModel->applicants->find($userId);

        SendApplicationResponseEmailJob::dispatch($applicant, $this->requestModel, 'rejected');
    }

    public function render()
    {
        $applicants = $this->requestModel->applicants;
        return view('livewire.request-applicant-manager', compact('applicants'));
    }
}

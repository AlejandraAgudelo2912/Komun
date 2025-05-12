<?php

namespace App\Jobs;

use App\Mail\ApplicationResponseMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendApplicationResponseEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $applicant;
    public $requestModel;
    public $status;

    public function __construct(User $applicant, $requestModel, $status)
    {
        $this->applicant = $applicant;
        $this->requestModel = $requestModel;
        $this->status = $status;
    }

    public function handle(): void
    {
        Mail::to($this->applicant->email)
            ->send(new ApplicationResponseMail($this->applicant, $this->requestModel, $this->status));
    }
}

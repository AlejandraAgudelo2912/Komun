<?php

use App\Events\AssistantVerificationDocumentEvent;
use App\Events\VerificationDocumentSubmittedEvent;
use App\Jobs\SendApplicationResponseEmailJob;
use App\Jobs\SendWelcomeEmail;
use App\Mail\ApplicationResponseMail;
use App\Mail\NewVerificationDocumentSubmittedMail;
use App\Mail\VerificationApprovedMail;
use App\Mail\VerificationRejectMail;
use App\Mail\WelcomeEmail;
use App\Models\Assistant;
use App\Models\AssistantVerificationDocument;
use App\Models\RequestModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
});

it('sends welcome email to new user', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    SendWelcomeEmail::dispatch($user);

    // Assert
    Mail::assertSent(WelcomeEmail::class, fn ($mail) => $mail->hasTo($user->email) &&
        $mail->envelope()->subject === '¡Bienvenido a Komun!'
    );
});

it('sends application response email to applicant', function () {
    // Arrange
    $applicant = User::factory()->create();
    $requestModel = RequestModel::factory()->create();
    $status = 'accepted';

    // Act
    SendApplicationResponseEmailJob::dispatch($applicant, $requestModel, $status);

    // Assert
    Mail::assertSent(ApplicationResponseMail::class, fn ($mail) => $mail->hasTo($applicant->email) &&
        $mail->envelope()->subject === 'Application Response' &&
        $mail->requestModel->id === $requestModel->id &&
        $mail->status === $status
    );
});

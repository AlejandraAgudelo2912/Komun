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

it('sends new verification document email to verificators', function () {
    // Arrange
    Event::fake();
    $verificator1 = User::factory()->create();
    $verificator2 = User::factory()->create();
    $verificator1->assignRole('verificator');
    $verificator2->assignRole('verificator');

    $assistant = Assistant::factory()->create();
    $document = AssistantVerificationDocument::factory()->create([
        'assistant_id' => $assistant->id,
    ]);

    // Act
    event(new VerificationDocumentSubmittedEvent($document));

    // Assert
    Mail::assertSent(NewVerificationDocumentSubmittedMail::class, fn ($mail) => ($mail->hasTo($verificator1->email) || $mail->hasTo($verificator2->email)) &&
        $mail->envelope()->subject === 'New Verification Document Submitted'
    );
})->skip();

it('sends verification approved email to assistant', function () {
    // Arrange
    Event::fake();
    $assistant = Assistant::factory()->create();
    $document = AssistantVerificationDocument::factory()->create([
        'assistant_id' => $assistant->id,
        'status' => 'approved',
    ]);

    // Act
    event(new AssistantVerificationDocumentEvent($document));

    // Assert
    Mail::assertSent(VerificationApprovedMail::class, fn ($mail) => $mail->hasTo($assistant->user->email) &&
        $mail->envelope()->subject === 'Verification Approved' &&
        $mail->assistant->id === $assistant->id
    );
})->skip();

it('sends verification reject email to assistant', function () {
    // Arrange
    Event::fake();
    $assistant = Assistant::factory()->create();
    $rejectionReason = 'Documentos insuficientes';
    $document = AssistantVerificationDocument::factory()->create([
        'assistant_id' => $assistant->id,
        'status' => 'rejected',
        'rejection_reason' => $rejectionReason,
    ]);

    // Act
    event(new AssistantVerificationDocumentEvent($document));

    // Assert
    Mail::assertSent(VerificationRejectMail::class, fn ($mail) => $mail->hasTo($assistant->user->email) &&
        $mail->envelope()->subject === 'Verification Reject' &&
        $mail->assistant->id === $assistant->id &&
        $mail->rejectionReason === $rejectionReason
    );
})->skip();

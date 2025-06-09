<?php


use App\Mail\NewVerificationDocumentSubmittedMail;
use App\Models\AssistantVerificationDocument;

it('crea el mail de nuevo documento de verificación enviado correctamente', function () {
    // arrange
    $document = AssistantVerificationDocument::factory()->make();

    // act
    $mail = new NewVerificationDocumentSubmittedMail($document);

    // assert
    expect($mail->assistantVerificationDocument)->toBe($document);
    expect($mail->envelope()->subject)->toBe('New Verification Document Submitted');
    expect($mail->content()->view)->toBe('emails.new-verification-document-submitted');
    expect($mail->attachments())->toBeArray()->toHaveCount(0);
});

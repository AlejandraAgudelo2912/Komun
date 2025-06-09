<?php

use App\Mail\VerificationRejectMail;
use App\Models\Assistant;

it('crea el mail de verificación rechazada correctamente', function () {
    // arrange
    $assistant = Assistant::factory()->make();
    $reason = 'Documentos inválidos';

    // act
    $mail = new VerificationRejectMail($assistant, $reason);

    // assert
    expect($mail->assistant)->toBe($assistant);
    expect($mail->rejectionReason)->toBe($reason);
    expect($mail->envelope()->subject)->toBe('Verification Reject');
    expect($mail->content()->view)->toBe('emails.verification-reject');
    expect($mail->attachments())->toBeArray()->toHaveCount(0);
});

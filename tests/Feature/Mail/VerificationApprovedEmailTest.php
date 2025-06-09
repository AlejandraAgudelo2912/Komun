<?php

use App\Mail\VerificationApprovedMail;
use App\Models\Assistant;

it('crea el mail de verificación aprobada correctamente', function () {
    // arrange
    $assistant = Assistant::factory()->make();

    // act
    $mail = new VerificationApprovedMail($assistant);

    // assert
    expect($mail->assistant)->toBe($assistant);
    expect($mail->envelope()->subject)->toBe('Verification Approved');
    expect($mail->content()->view)->toBe('emails.verification-approved');
    expect($mail->attachments())->toBeArray()->toHaveCount(0);
});

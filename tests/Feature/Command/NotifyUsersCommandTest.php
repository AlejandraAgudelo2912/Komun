<?php

use App\Mail\GeneralNotificationMail;
use App\Models\User;

it('informa si no hay admins que notificar', function () {
    // arrange
    User::whereHas('roles', function ($q) {
        $q->where('name', 'admin');
    })->delete(); // nos aseguramos que no haya admins

    // act
    $this->artisan('admins:notify')
        ->expectsOutput('Starting to send emails to admins...')
        ->expectsOutput('There are no admins to notify.')
        ->assertExitCode(0);

    // assert implícito con expectsOutput y assertExitCode
});

it('envía emails a todos los admins y registra logs', function () {
    // arrange
    Mail::fake();
    Log::shouldReceive('info')->once()->withArgs(function ($message) {
        return str_contains($message, 'Email sent to admin with ID');
    });

    $admin = User::factory()->create();
    $admin->assignRole('admin');  // Importante: asignar el rol admin

    $subject = 'Mi asunto especial';

    // act
    $this->artisan('admins:notify', ['--subject' => $subject])
        ->expectsOutput('Starting to send emails to admins...')
        ->expectsOutput('Found Admins:1')
        ->expectsOutput("Email sent to: {$admin->email}")
        ->expectsOutput('Sent emails to all admins.')
        ->assertExitCode(0);

    // assert
    Mail::assertQueued(GeneralNotificationMail::class, function ($mail) use ($admin) {
        return $mail->hasTo($admin->email);
        // si GeneralNotificationMail no tiene una propiedad pública subject, no compares
    });
});

it('muestra error si falla el envío de un email', function () {
    // arrange
    Mail::shouldReceive('to->queue')->andThrow(new \Exception('Fallo al enviar'));

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // act
    $this->artisan('admins:notify')
        ->expectsOutput('Starting to send emails to admins...')
        ->expectsOutput('Found Admins:1')
        ->expectsOutput("Error enviando email a {$admin->email}: Fallo al enviar")
        ->assertExitCode(0);
});

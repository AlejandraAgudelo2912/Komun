<?php

namespace App\Jobs;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Enviando correo de bienvenida a: ' . $this->user->email);
        
        try {
            Mail::to($this->user->email)->send(new WelcomeEmail($this->user));
            Log::info('Correo de bienvenida enviado exitosamente a: ' . $this->user->email);
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de bienvenida: ' . $e->getMessage());
            throw $e;
        }
    }
}

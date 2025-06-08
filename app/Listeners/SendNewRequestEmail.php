<?php

namespace App\Listeners;

use App\Events\NewRequestCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNewRequestEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewRequestCreated $event): void
    {
        $request = $event->request;
        $category = $request->category;

        // Obtener todos los usuarios que siguen esta categoría y tienen las notificaciones activadas
        $followers = $category->followers()
            ->wherePivot('notifications_enabled', true)
            ->get();

        foreach ($followers as $follower) {
            Mail::send('emails.new-request', [
                'user' => $follower,
                'request' => $request,
                'category' => $category
            ], function ($message) use ($follower, $request, $category) {
                $message->to($follower->email)
                    ->subject('Nueva solicitud en ' . $category->name)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
        }
    }
}

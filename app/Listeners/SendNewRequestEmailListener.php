<?php

namespace App\Listeners;

use App\Events\NewRequestCreatedEvent;
use App\Mail\NewRequestMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNewRequestEmailListener implements ShouldQueue
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
    public function handle(NewRequestCreatedEvent $event): void
    {
        $request = $event->request;
        $category = $request->category;

        $followers = $category->followers()->get();

        foreach ($followers as $follower) {
            Mail::to($follower->email)->send(new NewRequestMail($request, $follower, $category));
        }
    }
}

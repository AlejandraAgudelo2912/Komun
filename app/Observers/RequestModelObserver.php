<?php

namespace App\Observers;

use App\Events\NewRequestCreatedEvent;
use App\Models\RequestModel;

class RequestModelObserver
{
    /**
     * Handle the RequestModel "created" event.
     */
    public function created(RequestModel $request): void
    {
        event(new NewRequestCreatedEvent($request));
    }
}

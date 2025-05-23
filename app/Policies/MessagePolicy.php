<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Message $message): bool
    {
        return $user->id === $message->user_id || $user->id === $message->receiver_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Message $message): bool
    {
        return $user->id === $message->user_id;
    }

    public function delete(User $user, Message $message): bool
    {
        return $user->id === $message->user_id || $user->id === $message->receiver_id;
    }

} 
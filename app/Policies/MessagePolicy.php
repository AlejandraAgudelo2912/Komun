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

    public function markAsRead(User $user, Message $message): bool
    {
        return $user->id === $message->receiver_id;
    }

    public function viewRequestMessages(User $user, Message $message): bool
    {
        if ($message->request_model_id) {
            $requestModel = $message->requestModel;
            
            if ($user->id === $requestModel->user_id) {
                return true;
            }

            if ($requestModel->applicants()
                ->where('user_id', $user->id)
                ->where('status', 'accepted')
                ->exists()) {
                return true;
            }

            return $user->hasRole(['admin', 'god']);
        }

        return false;
    }
} 
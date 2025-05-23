<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Comment $comment): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true; 
    }

    public function update(User $user, Comment $comment): bool
    {
        if ($user->id === $comment->user_id) {
            return true;
        }

        return $user->hasRole(['admin', 'god']);
    }

    public function delete(User $user, Comment $comment): bool
    {
        if ($user->id === $comment->user_id) {
            return true;
        }

        if ($user->id === $comment->requestModel->user_id) {
            return true;
        }

        return $user->hasRole(['admin', 'god']);
    }

    public function moderate(User $user, Comment $comment): bool
    {
        return $user->hasRole(['admin', 'god', 'moderator']);
    }
} 
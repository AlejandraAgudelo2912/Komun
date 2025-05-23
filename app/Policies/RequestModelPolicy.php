<?php

namespace App\Policies;

use App\Models\RequestModel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestModelPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; 
    }

    public function view(User $user, RequestModel $requestModel): bool
    {
        return true; 
    }

    public function create(User $user): bool
    {
        return true; 
    }

    public function update(User $user, RequestModel $requestModel): bool
    {
        if ($user->id === $requestModel->user_id && $requestModel->status === 'pending') {
            return true;
        }

        return $user->hasRole(['admin', 'god']);
    }

    public function delete(User $user, RequestModel $requestModel): bool
    {
        if ($user->id === $requestModel->user_id && $requestModel->status === 'pending') {
            return true;
        }

        return $user->hasRole(['admin', 'god']);
    }

    public function apply(User $user, RequestModel $requestModel): bool
    {
        if (!$user->hasRole('assistant')) {
            return false;
        }

        if ($user->id === $requestModel->user_id) {
            return false;
        }

        if ($requestModel->status !== 'pending') {
            return false;
        }

        if ($requestModel->max_applications && 
            $requestModel->applicants()->count() >= $requestModel->max_applications) {
            return false;
        }

        if ($requestModel->applicants()->where('user_id', $user->id)->exists()) {
            return false;
        }

        return true;
    }

    public function viewApplicants(User $user, RequestModel $requestModel): bool
    {
        if ($user->id === $requestModel->user_id) {
            return true;
        }

        return $user->hasRole(['admin', 'god']);
    }
} 
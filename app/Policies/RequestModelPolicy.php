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
        \Log::info('Verificando política de actualización', [
            'user_id' => $user->id,
            'request_user_id' => $requestModel->user_id,
            'user_roles' => $user->getRoleNames(),
            'is_owner' => (string) $user->id === (string) $requestModel->user_id,
            'has_admin_role' => $user->hasRole(['admin', 'god'])
        ]);

        if ((string) $user->id === (string) $requestModel->user_id) {
            \Log::info('Usuario es propietario de la solicitud');
            return true;
        }

        $hasAdminRole = $user->hasRole(['admin', 'god']);
        \Log::info('Usuario tiene rol de administrador', ['has_admin_role' => $hasAdminRole]);
        return $hasAdminRole;
    }

    public function delete(User $user, RequestModel $requestModel): bool
    {
        if ($user->id === $requestModel->user_id) {
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

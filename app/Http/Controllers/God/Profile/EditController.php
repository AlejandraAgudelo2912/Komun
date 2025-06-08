<?php

namespace App\Http\Controllers\God\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EditController extends Controller
{
    public function __invoke(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('god.profiles.edit', [
            'user' => $user->load(['roles', 'permissions', 'assistant', 'assistant.verification']),
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }
}

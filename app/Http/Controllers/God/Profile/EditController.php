<?php

namespace App\Http\Controllers\God\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class EditController extends Controller
{
    public function __invoke(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('god.profiles.edit', [
            'user' => $user->load(['roles', 'permissions', 'assistant', 'assistant.verification']),
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }
} 
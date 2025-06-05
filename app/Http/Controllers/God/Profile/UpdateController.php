<?php

namespace App\Http\Controllers\God\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
            'is_verified' => ['boolean'],
            'status' => ['nullable', 'string', Rule::in(['active', 'inactive', 'suspended'])],
        ]);

        // Actualizar datos básicos
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Actualizar contraseña si se proporciona
        if ($validated['password']) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Actualizar roles
        $user->syncRoles($validated['roles']);

        // Actualizar permisos si se proporcionan
        if (isset($validated['permissions'])) {
            $user->syncPermissions($validated['permissions']);
        }

        // Actualizar estado del asistente si existe
        if ($user->assistant) {
            $user->assistant->update([
                'is_verified' => $validated['is_verified'],
                'status' => $validated['status'] ?? 'active',
            ]);
        }

        return redirect()->route('god.profiles.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
} 
<?php

namespace App\Http\Controllers\God\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        // Verificar que no sea el último administrador
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return redirect()->route('god.profiles.index')
                ->with('error', 'No se puede eliminar el último administrador.');
        }

        // Verificar que no sea el último superadmin
        if ($user->hasRole('super-admin') && User::role('super-admin')->count() <= 1) {
            return redirect()->route('god.profiles.index')
                ->with('error', 'No se puede eliminar el último super administrador.');
        }

        try {
            DB::beginTransaction();

            // Si el usuario es asistente, eliminar primero los documentos de verificación
            if ($user->assistant) {
                // Eliminar documentos de verificación
                if ($user->assistant->verification) {
                    $user->assistant->verification->delete();
                }

                // Eliminar el registro de asistente
                $user->assistant->delete();
            }

            // Eliminar roles y permisos
            $user->roles()->detach();
            $user->permissions()->detach();

            // Finalmente eliminar el usuario
            $user->delete();

            DB::commit();

            return redirect()->route('god.profiles.index')
                ->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('god.profiles.index')
                ->with('error', 'Error al eliminar el usuario: '.$e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *     name="Usuarios",
 *     description="API Endpoints para la gestión de usuarios"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Put(
     *     path="/api/users/profile",
     *     summary="Actualizar el perfil del usuario autenticado",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="name", type="string", example="Juan Pérez"),
     *             @OA\Property(property="username", type="string", example="juanperez"),
     *             @OA\Property(property="email", type="string", example="juan@example.com"),
     *             @OA\Property(property="password", type="string", example="nuevapassword"),
     *             @OA\Property(property="password_confirmation", type="string", example="nuevapassword"),
     *             @OA\Property(property="phone", type="string", example="123456789"),
     *             @OA\Property(property="address", type="string", example="Calle Principal 123"),
     *             @OA\Property(property="bio", type="string", example="Descripción del usuario")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Perfil actualizado exitosamente",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Perfil actualizado correctamente"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function update(Request $request)
    {
        if (Gate::denies('update', Auth::user())) {
            return response()->json(['message' => 'No tienes permiso para actualizar el perfil'], 403);
        }

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'name', 'email',
        ]);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user' => $user->fresh(),
        ], 200);

    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Obtener un usuario específico",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del usuario",
     *
     *         @OA\JsonContent(type="object")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $user = User::with(['reviews', 'requests'])
                ->findOrFail($id);

            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Eliminar un usuario (solo para administradores)",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a eliminar",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado exitosamente",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Usuario eliminado correctamente")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="No tienes permiso para eliminar usuarios")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $userToDelete = User::findOrFail($id);

        if (Gate::denies('delete', $userToDelete)) {
            return response()->json(['message' => 'No tienes permiso para eliminar usuarios'], 403);
        }

        try {

            // Eliminar documentos de verificación del asistente
            if ($userToDelete->assistant) {
                $userToDelete->assistant->verification()->delete();
                $userToDelete->assistant()->delete();
            }

            // Eliminar todas las relaciones
            $userToDelete->reviews()->delete();
            $userToDelete->requests()->delete();

            // Eliminar el usuario
            $userToDelete->delete();

            \Log::info('Usuario eliminado exitosamente', [
                'deleted_user_id' => $id,
                'deleted_by_user_id' => $user->id,
            ]);

            return response()->json(['message' => 'Usuario eliminado correctamente']);
        } catch (\Exception $e) {

            \Log::error('Error al eliminar usuario', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Error al eliminar el usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Listar todos los usuarios",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Término de búsqueda para filtrar usuarios por nombre, email o username",
     *         required=false,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="Filtrar usuarios por rol",
     *         required=false,
     *
     *         @OA\Schema(type="string", enum={"user", "admin", "god"})
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios obtenida exitosamente",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *                 type="object",
     *
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Juan Pérez"),
     *                 @OA\Property(property="email", type="string", example="juan@example.com"),
     *                 @OA\Property(property="username", type="string", example="juanperez"),
     *                 @OA\Property(property="role", type="string", example="user"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="No autenticado")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            \Log::info('Intentando obtener lista de usuarios', [
                'user_id' => Auth::id(),
                'filters' => $request->all(),
            ]);

            $query = User::query();

            // Aplicar filtro de búsqueda si existe
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%")
                        ->orWhere('username', 'like', "%{$searchTerm}%");
                });
            }

            // Aplicar filtro de rol si existe
            if ($request->has('role')) {
                $query->where('role', $request->role);
            }

            // Obtener usuarios con sus relaciones básicas
            $users = $query->with(['reviews', 'requests'])
                ->orderBy('created_at', 'desc')
                ->get();

            \Log::info('Lista de usuarios obtenida exitosamente', [
                'total_users' => $users->count(),
            ]);

            return response()->json($users, 200);
        } catch (\Exception $e) {
            \Log::error('Error al obtener lista de usuarios', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Error al obtener la lista de usuarios',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

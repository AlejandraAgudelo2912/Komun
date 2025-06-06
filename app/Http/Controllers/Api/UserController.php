<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RequestModel;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * @OA\Get(
     *     path="/api/users/profile",
     *     summary="Obtener el perfil del usuario autenticado",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Perfil del usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Juan Pérez"),
     *             @OA\Property(property="username", type="string", example="juanperez"),
     *             @OA\Property(property="email", type="string", example="juan@example.com"),
     *             @OA\Property(property="phone", type="string", example="123456789"),
     *             @OA\Property(property="address", type="string", example="Calle Principal 123"),
     *             @OA\Property(property="bio", type="string", example="Descripción del usuario"),
     *             @OA\Property(property="is_assistant", type="boolean", example=false),
     *             @OA\Property(property="is_admin", type="boolean", example=false),
     *             @OA\Property(
     *                 property="reviews",
     *                 type="array",
     *                 @OA\Items(type="object")
     *             ),
     *             @OA\Property(
     *                 property="requests",
     *                 type="array",
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No autenticado")
     *         )
     *     )
     * )
     */
    public function profile()
    {
        $user = Auth::user()->load(['reviews', 'requests']);
        return response()->json($user, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/users/profile",
     *     summary="Actualizar el perfil del usuario autenticado",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
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
     *     @OA\Response(
     *         response=200,
     *         description="Perfil actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Perfil actualizado correctamente"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function updateProfile(Request $request)
    {

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'username' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'sometimes|string|min:8|confirmed',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'name', 'username', 'email', 'phone',
            'address', 'bio'
        ]);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user' => $user->fresh()
        ], 200);

    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Obtener un usuario específico",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del usuario",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
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
     * @OA\Get(
     *     path="/api/users/my-requests",
     *     summary="Obtener las solicitudes del usuario autenticado",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de solicitudes",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function myRequests()
    {
        try {
            $requests = Auth::user()
                          ->requests()
                          ->with(['assistant:id,username', 'reviews'])
                          ->orderBy('created_at', 'desc')
                          ->get();

            return response()->json($requests, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener las solicitudes', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/my-reviews",
     *     summary="Obtener las reviews realizadas por el usuario autenticado",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de reviews",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function myReviews()
    {
        try {
            $reviews = Auth::user()
                         ->reviews()
                         ->with(['assistant:id,username', 'request'])
                         ->orderBy('created_at', 'desc')
                         ->get();

            return response()->json($reviews, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener las reviews', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/assisted-requests",
     *     summary="Obtener las solicitudes atendidas por el usuario (solo para asistentes)",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de solicitudes atendidas",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No tienes permiso para acceder a este recurso")
     *         )
     *     )
     * )
     */
    public function assistedRequests()
    {
        try {

            $requests = RequestModel::where('assistant_id', Auth::id())
                                  ->with(['user:id,username', 'reviews'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();

            return response()->json($requests, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener las solicitudes atendidas', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/received-reviews",
     *     summary="Obtener las reviews recibidas por el usuario (solo para asistentes)",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de reviews recibidas",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No tienes permiso para acceder a este recurso")
     *         )
     *     )
     * )
     */
    public function receivedReviews()
    {
        try {

            $reviews = Review::where('assistant_id', Auth::id())
                           ->with(['user:id,username', 'request'])
                           ->orderBy('created_at', 'desc')
                           ->get();

            return response()->json($reviews, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener las reviews recibidas', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/update-assistant-status",
     *     summary="Actualizar el estado de asistente de un usuario (solo para administradores)",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "is_assistant"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="is_assistant", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado de asistente actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Estado de asistente actualizado correctamente"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No tienes permiso para realizar esta acción")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function updateAssistantStatus(Request $request)
    {
        try {
            $user = User::findOrFail($request->user_id);

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'is_assistant' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user->update(['is_assistant' => $request->is_assistant]);

            return response()->json([
                'message' => 'Estado de asistente actualizado correctamente',
                'user' => $user->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el estado de asistente', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Eliminar un usuario (solo para administradores)",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No tienes permiso para eliminar usuarios")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Eliminar todas las relaciones primero
            $user->reviews()->delete();
            $user->requests()->delete();

            // Eliminar el usuario
            $user->delete();

            return response()->json(['message' => 'Usuario eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el usuario', 'error' => $e->getMessage()], 500);
        }
    }
}

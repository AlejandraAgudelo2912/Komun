<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Tag(
 *     name="Solicitudes",
 *     description="API Endpoints para la gestión de solicitudes"
 * )
 */
class RequestModelController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/requests",
     *     summary="Listar todas las solicitudes",
     *     tags={"Solicitudes"},
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar por estado",
     *
     *         @OA\Schema(type="string", enum={"pending", "in_progress", "completed", "cancelled"})
     *     ),
     *
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filtrar por categoría",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Buscar por título o descripción",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de solicitudes",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *                 type="object",
     *
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Necesito ayuda con limpieza"),
     *                 @OA\Property(property="description", type="string", example="Busco ayuda para limpiar mi casa"),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="assistant_id", type="integer", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="category",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Limpieza")
     *                 ),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Juan Pérez")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = RequestModel::with(['category', 'user', 'assistant'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->category_id, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            });

        if (Auth::check()) {
            $query->when(Auth::user()->role === 'needHelp', function ($query) {
                return $query->where('user_id', Auth::id());
            })
                ->when(Auth::user()->role === 'assistant', function ($query) {
                    return $query->where('status', 'open');
                });
        }

        $requests = $query->latest()->paginate(10);

        return response()->json($requests);
    }

    /**
     * @OA\Post(
     *     path="/api/requests",
     *     summary="Crear una nueva solicitud",
     *     tags={"Solicitudes"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"title","description","category_id"},
     *
     *             @OA\Property(property="title", type="string", example="Necesito ayuda con limpieza"),
     *             @OA\Property(property="description", type="string", example="Busco ayuda para limpiar mi casa"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="location", type="string", example="Calle Falsa 123"),
     *             @OA\Property(property="deadline", type="string", format="date", example="2026-12-31")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Solicitud creada exitosamente",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Solicitud creada exitosamente"),
     *             @OA\Property(property="request", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(Request $request)
    {
        if (Gate::denies('create', RequestModel::class)) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date|after:today',
            'status' => 'sometimes|in:open,closed,in_progress,completed',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'open';

        $requestModel = RequestModel::create($validated);

        return response()->json([
            'message' => 'Solicitud creada exitosamente',
            'request' => $requestModel->load(['category', 'user']),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/requests/{id}",
     *     summary="Obtener una solicitud específica",
     *     tags={"Solicitudes"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la solicitud",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la solicitud",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Necesito ayuda con limpieza"),
     *             @OA\Property(property="description", type="string", example="Busco ayuda para limpiar mi casa"),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="assistant_id", type="integer", nullable=true),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(
     *                 property="category",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Limpieza")
     *             ),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Juan Pérez")
     *             ),
     *             @OA\Property(
     *                 property="assistant",
     *                 type="object",
     *                 nullable=true,
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="María García")
     *             ),
     *             @OA\Property(
     *                 property="comments",
     *                 type="array",
     *
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud no encontrada"
     *     )
     * )
     */
    public function show(RequestModel $requestModel)
    {
        return response()->json($requestModel->load(['category', 'user', 'assistant', 'comments', 'applicants']));
    }

    /**
     * @OA\Put(
     *     path="/api/requests/{id}",
     *     summary="Actualizar una solicitud",
     *     tags={"Solicitudes"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la solicitud",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"title","description","category_id","location","deadline"},
     *
     *             @OA\Property(property="title", type="string", example="Necesito ayuda con limpieza"),
     *             @OA\Property(property="description", type="string", example="Busco ayuda para limpiar mi casa"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="location", type="string", example="Calle Falsa 123"),
     *             @OA\Property(property="deadline", type="string", format="date", example="2024-12-31"),
     *             @OA\Property(property="status", type="string", enum={"open", "closed", "in_progress", "completed"}, example="open")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Solicitud actualizada exitosamente",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Solicitud actualizada exitosamente"),
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Necesito ayuda con limpieza"),
     *                 @OA\Property(property="description", type="string", example="Busco ayuda para limpiar mi casa"),
     *                 @OA\Property(property="status", type="string", example="open"),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="location", type="string", example="Calle Falsa 123"),
     *                 @OA\Property(property="deadline", type="string", format="date", example="2024-12-31"),
     *                 @OA\Property(
     *                     property="category",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Limpieza")
     *                 ),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Juan Pérez")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud no encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $requestModel = RequestModel::findOrFail($id);

        \Log::info('Intentando actualizar solicitud', [
            'user_id' => auth()->id(),
            'request_user_id' => $requestModel->user_id,
            'user_roles' => auth()->user()->getRoleNames(),
            'user_permissions' => auth()->user()->getAllPermissions()->pluck('name'),
            'is_owner' => auth()->id() === $requestModel->user_id,
            'has_admin_role' => auth()->user()->hasRole(['admin', 'god']),
        ]);

        if (Gate::denies('update', $requestModel)) {
            \Log::warning('Acceso denegado al actualizar solicitud', [
                'user_id' => auth()->id(),
                'request_id' => $requestModel->id,
            ]);

            return response()->json(['message' => 'No tienes permiso para actualizar esta solicitud'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date|after:today',
            'status' => 'sometimes|in:open,closed,in_progress,completed',
        ]);

        $requestModel->update($validated);

        \Log::info('Solicitud actualizada exitosamente', [
            'user_id' => auth()->id(),
            'request_id' => $requestModel->id,
        ]);

        return response()->json([
            'message' => 'Solicitud actualizada exitosamente',
            'request' => $requestModel->load(['category', 'user']),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/requests/{id}",
     *     summary="Eliminar una solicitud",
     *     tags={"Solicitudes"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la solicitud",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Solicitud eliminada exitosamente",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Solicitud eliminada exitosamente")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud no encontrada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $requestModel = RequestModel::findOrFail($id);

        \Log::info('Intentando eliminar solicitud', [
            'user_id' => auth()->id(),
            'request_user_id' => $requestModel->user_id,
            'user_roles' => auth()->user()->getRoleNames(),
            'user_permissions' => auth()->user()->getAllPermissions()->pluck('name'),
            'is_owner' => auth()->id() === $requestModel->user_id,
            'has_admin_role' => auth()->user()->hasRole(['admin', 'god']),
        ]);

        if (Gate::denies('delete', $requestModel)) {
            \Log::warning('Acceso denegado al eliminar solicitud', [
                'user_id' => auth()->id(),
                'request_id' => $requestModel->id,
            ]);

            return response()->json(['message' => 'No tienes permiso para eliminar esta solicitud'], 403);
        }

        $requestModel->delete();

        \Log::info('Solicitud eliminada exitosamente', [
            'user_id' => auth()->id(),
            'request_id' => $id,
        ]);

        return response()->json(['message' => 'Solicitud eliminada exitosamente']);
    }
}

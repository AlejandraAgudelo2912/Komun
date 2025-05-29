<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Comentarios",
 *     description="API Endpoints para la gestión de comentarios"
 * )
 */
class CommentController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/requests/{request_id}/comments",
     *     summary="Listar comentarios de una solicitud",
     *     tags={"Comentarios"},
     *     @OA\Parameter(
     *         name="request_id",
     *         in="path",
     *         required=true,
     *         description="ID de la solicitud",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de comentarios",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="content", type="string", example="Excelente servicio"),
     *                 @OA\Property(property="request_id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Juan Pérez")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud no encontrada"
     *     )
     * )
     */
    public function index(RequestModel $request)
    {
        $comments = $request->comments()
            ->with('user')
            ->latest()
            ->paginate(10);

        return response()->json($comments);
    }

    /**
     * @OA\Post(
     *     path="/api/requests/{request_id}/comments",
     *     summary="Crear un nuevo comentario",
     *     tags={"Comentarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="request_id",
     *         in="path",
     *         required=true,
     *         description="ID de la solicitud",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", example="Excelente trabajo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comentario creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comentario creado exitosamente"),
     *             @OA\Property(property="comment", type="object")
     *         )
     *     ),
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
    public function store(Request $request, RequestModel $requestModel)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:1000'
        ]);

        $comment = $requestModel->comments()->create([
            'body' => $validated['body'],
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'message' => 'Comentario creado exitosamente',
            'comment' => $comment->load('user')
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/comments/{id}",
     *     summary="Obtener un comentario específico",
     *     tags={"Comentarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del comentario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del comentario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="content", type="string", example="Excelente trabajo"),
     *             @OA\Property(property="request_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Juan Pérez")
     *             )
     *         )
     *     ),
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
     *         description="Comentario no encontrado"
     *     )
     * )
     */
    public function show(Comment $comment)
    {
        return response()->json($comment->load('user'));
    }

    /**
     * @OA\Put(
     *     path="/api/comments/{id}",
     *     summary="Actualizar un comentario",
     *     tags={"Comentarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del comentario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", example="Excelente trabajo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comentario actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comentario actualizado exitosamente"),
     *             @OA\Property(property="comment", type="object")
     *         )
     *     ),
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
     *         description="Comentario no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:1000'
        ]);

        $comment->update($validated);

        return response()->json([
            'message' => 'Comentario actualizado exitosamente',
            'comment' => $comment->load('user')
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/comments/{id}",
     *     summary="Eliminar un comentario",
     *     tags={"Comentarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del comentario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comentario eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comentario eliminado exitosamente")
     *         )
     *     ),
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
     *         description="Comentario no encontrado"
     *     )
     * )
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'message' => 'Comentario eliminado exitosamente'
        ]);
    }

}

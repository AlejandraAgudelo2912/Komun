<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Reviews",
 *     description="API Endpoints para la gestión de reviews"
 * )
 */
class ReviewController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reviews",
     *     summary="Obtener todas las reviews",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de reviews",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *                 type="object",
     *
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="rating", type="integer", example=5),
     *                 @OA\Property(property="comment", type="string", example="Excelente servicio"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="assistant_id", type="integer", example=2),
     *                 @OA\Property(property="request_models_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="username", type="string", example="usuario1")
     *                 ),
     *                 @OA\Property(
     *                     property="assistant",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="username", type="string", example="asistente1")
     *                 ),
     *                 @OA\Property(
     *                     property="request",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Solicitud de ayuda")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $reviews = Review::with(['user:id', 'assistant:id', 'request'])
            ->get();

        return response()->json($reviews, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/reviews/{id}",
     *     summary="Obtener una review específica",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la review",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la review",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="rating", type="integer", example=5),
     *             @OA\Property(property="comment", type="string", example="Excelente servicio"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="assistant_id", type="integer", example=2),
     *             @OA\Property(property="request_models_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="username", type="string", example="usuario1")
     *             ),
     *             @OA\Property(
     *                 property="assistant",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="username", type="string", example="asistente1")
     *             ),
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Solicitud de ayuda")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Review no encontrada",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Review no encontrada")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $review = Review::with(['user:id', 'assistant:id', 'request'])
            ->find($id);
        if (! $review) {
            return response()->json(['message' => 'Review no encontrada'], 404);
        }

        return response()->json($review, 200);

    }

    /**
     * @OA\Post(
     *     path="/api/reviews",
     *     summary="Crear una nueva review",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"rating", "comment", "request_models_id", "assistant_id"},
     *
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
     *             @OA\Property(property="comment", type="string", maxLength=1000, example="Excelente servicio"),
     *             @OA\Property(property="request_models_id", type="integer", example=1),
     *             @OA\Property(property="assistant_id", type="integer", example=2)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Review creada exitosamente",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="rating", type="integer", example=5),
     *             @OA\Property(property="comment", type="string", example="Excelente servicio"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="assistant_id", type="integer", example=2),
     *             @OA\Property(property="request_models_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="username", type="string", example="usuario1")
     *             ),
     *             @OA\Property(
     *                 property="assistant",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="username", type="string", example="asistente1")
     *             ),
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Solicitud de ayuda")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Error en la solicitud",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="El asistente especificado no está asignado a esta solicitud")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="No tienes permiso para calificar esta solicitud")
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
    public function store(Request $request)
    {
        if (! Gate::allows('create', Review::class)) {
            return response()->json(['message' => 'No tienes permiso para calificar esta solicitud'], 403);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'request_models_id' => 'required|exists:request_models,id',
            'assistant_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'assistant_id' => $request->assistant_id,
            'request_models_id' => $request->request_models_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json($review->load(['user:id', 'assistant:id', 'request']), 201);
    }

    /**
     * @OA\Put(
     *     path="/api/reviews/{id}",
     *     summary="Actualizar una review",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la review",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
     *             @OA\Property(property="comment", type="string", maxLength=1000, example="Excelente servicio")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Review actualizada exitosamente",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="rating", type="integer", example=5),
     *             @OA\Property(property="comment", type="string", example="Excelente servicio"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="assistant_id", type="integer", example=2),
     *             @OA\Property(property="request_models_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="username", type="string", example="usuario1")
     *             ),
     *             @OA\Property(
     *                 property="assistant",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="username", type="string", example="asistente1")
     *             ),
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Solicitud de ayuda")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="No tienes permiso para actualizar esta review")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Review no encontrada",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Review no encontrada")
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
    public function update(Request $request, $id)
    {
        $review = Review::find($id);
        if (! Gate::allows('update', $review)) {
            return response()->json(['message' => 'No tienes permiso para actualizar esta review'], 403);
        }
        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'sometimes|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $review = Review::find($id);

        if (! $review) {
            return response()->json(['message' => 'Review no encontrada'], 404);
        }

        if ($review->user_id !== Auth::id()) {
            return response()->json(['message' => 'No tienes permiso para actualizar esta review'], 403);
        }

        $review->update($request->only(['rating', 'comment']));

        return response()->json($review->load(['user:id', 'assistant:id', 'request']), 200);

    }

    /**
     * @OA\Delete(
     *     path="/api/reviews/{id}",
     *     summary="Eliminar una review",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la review",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Review eliminada exitosamente",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Review eliminada correctamente")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="No tienes permiso para eliminar esta review")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Review no encontrada",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Review no encontrada")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $review = Review::find($id);
        if (! Gate::allows('delete', $review)) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta review'], 403);
        }
        $review = Review::find($id);

        if (! $review) {
            return response()->json(['message' => 'Review no encontrada'], 404);
        }

        if ($review->user_id !== Auth::id()) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta review'], 403);
        }

        $review->delete();

        return response()->json(['message' => 'Review eliminada correctamente'], 200);

    }
}

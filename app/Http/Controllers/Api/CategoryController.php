<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Tag(
 *     name="Categorías",
 *     description="API Endpoints para la gestión de categorías"
 * )
 */
class CategoryController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Listar todas las categorías",
     *     tags={"Categorías"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorías",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Limpieza"),
     *                 @OA\Property(property="description", type="string", example="Servicios de limpieza"),
     *                 @OA\Property(property="icon", type="string", example="broom"),
     *                 @OA\Property(property="color", type="string", example="#FF0000"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Crear una nueva categoría",
     *     tags={"Categorías"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","description"},
     *             @OA\Property(property="name", type="string", example="Limpieza"),
     *             @OA\Property(property="description", type="string", example="Servicios de limpieza"),
     *             @OA\Property(property="icon", type="string", example="broom"),
     *             @OA\Property(property="color", type="string", example="#FF0000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoría creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Categoría creada exitosamente"),
     *             @OA\Property(property="category", type="object")
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
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'color' => 'nullable|string'
        ]);

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Categoría creada exitosamente',
            'category' => $category
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Obtener una categoría específica",
     *     tags={"Categorías"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la categoría",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Limpieza"),
     *             @OA\Property(property="description", type="string", example="Servicios de limpieza"),
     *             @OA\Property(property="icon", type="string", example="broom"),
     *             @OA\Property(property="color", type="string", example="#FF0000"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
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
     *         description="Categoría no encontrada"
     *     )
     * )
     */
    public function show(Category $category)
    {
        return response()->json($category);
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Actualizar una categoría",
     *     tags={"Categorías"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","description"},
     *             @OA\Property(property="name", type="string", example="Limpieza"),
     *             @OA\Property(property="description", type="string", example="Servicios de limpieza"),
     *             @OA\Property(property="icon", type="string", example="broom"),
     *             @OA\Property(property="color", type="string", example="#FF0000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Categoría actualizada exitosamente"),
     *             @OA\Property(property="category", type="object")
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
     *         description="Categoría no encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'color' => 'nullable|string'
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Categoría actualizada exitosamente',
            'category' => $category
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Eliminar una categoría",
     *     tags={"Categorías"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Categoría eliminada exitosamente")
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
     *         description="Categoría no encontrada"
     *     )
     * )
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Categoría eliminada exitosamente'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/categories/{id}/restore",
     *     summary="Restaurar una categoría eliminada",
     *     tags={"Categorías"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría restaurada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Categoría restaurada exitosamente"),
     *             @OA\Property(property="category", type="object")
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
     *         description="Categoría no encontrada"
     *     )
     * )
     */
    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();

        return response()->json([
            'message' => 'Categoría restaurada exitosamente',
            'category' => $category
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}/force",
     *     summary="Eliminar permanentemente una categoría",
     *     tags={"Categorías"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría eliminada permanentemente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Categoría eliminada permanentemente")
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
     *         description="Categoría no encontrada"
     *     )
     * )
     */
    public function forceDelete($id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        $category->forceDelete();

        return response()->json([
            'message' => 'Categoría eliminada permanentemente'
        ]);
    }
} 
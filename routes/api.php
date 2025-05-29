<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\CommentController;

// Rutas públicas
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/requests/{requestModel}/comments', [CommentController::class, 'index']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Categorías
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);

    // Solicitudes
    Route::apiResource('requests', RequestController::class);
    Route::post('/requests/{requestModel}/apply', [RequestController::class, 'apply']);
    Route::post('/requests/{requestModel}/verify', [RequestController::class, 'verify']);

    // Comentarios

    Route::post('/requests/{requestModel}/comments', [CommentController::class, 'store']);
    Route::apiResource('comments', CommentController::class)->except(['index', 'store']);
    Route::post('/comments/{comment}/moderate', [CommentController::class, 'moderate']);
});

<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\RequestModelController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/requests/{requestModel}/comments', [CommentController::class, 'index']);
Route::get('/comments/{comment}', [CommentController::class, 'show']);
Route::get('/requests/{requestModel}', [RequestModelController::class, 'show']);
Route::get('/requests', [RequestModelController::class, 'index']);
Route::get('/reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'show']);
Route::get('/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('requests', RequestModelController::class)->except(['index', 'show']);

    Route::post('/requests/{requestModel}/comments', [CommentController::class, 'store']);
    Route::apiResource('comments', CommentController::class)->except(['index', 'show']);

    Route::apiResource('reviews', \App\Http\Controllers\Api\ReviewController::class)
        ->only(['store', 'update', 'destroy']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});

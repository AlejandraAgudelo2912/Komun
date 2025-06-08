<?php

use App\Http\Controllers\AssistantVerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:verificator'])->prefix('verificator')->name('verificator.')->group(function () {
    Route::get('/dashboard', App\Http\Controllers\Verificator\DashboardController::class)->name('dashboard');

    Route::get('/verifications', [AssistantVerificationController::class, 'index'])->name('verifications.index');
    Route::post('/verifications/{id}/approve', [AssistantVerificationController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifications/{id}/reject', [AssistantVerificationController::class, 'reject'])->name('verifications.reject');

    Route::get('/categories', App\Http\Controllers\Verificator\Category\IndexController::class)->name('categories.index');
    Route::get('/categories/{category}', App\Http\Controllers\Verificator\Category\ShowController::class)
        ->middleware('can:view,category')
        ->name('categories.show');

    Route::get('/requests', App\Http\Controllers\Verificator\Request\IndexController::class)
        ->middleware('can:viewAny,App\Models\RequestModel')
        ->name('requests.index');

    Route::get('/requests/filter', [App\Http\Controllers\FilterController::class, 'filter'])
        ->middleware('can:viewAny,App\Models\RequestModel')
        ->name('requests.filter');

    Route::get('/requests/create', App\Http\Controllers\Verificator\Request\CreateController::class)->name('requests.create');
    Route::post('/requests', App\Http\Controllers\Verificator\Request\StoreController::class)->name('requests.store');
    Route::get('/requests/{request}/edit', App\Http\Controllers\Verificator\Request\EditController::class)->name('requests.edit');
    Route::put('/requests/{request}', App\Http\Controllers\Verificator\Request\UpdateController::class)->name('requests.update');
    Route::delete('/requests/{request}', App\Http\Controllers\Verificator\Request\DestroyController::class)->name('requests.destroy');

    // Rutas para reseñas
    Route::get('/requests/{requestModel}/review', \App\Http\Controllers\Verificator\Review\CreateController::class)
        ->name('reviews.create');
    Route::post('/requests/{requestModel}/review', \App\Http\Controllers\Verificator\Review\StoreController::class)
        ->name('reviews.store');
    Route::get('/requests/{requestModel}/review/edit', \App\Http\Controllers\Verificator\Review\EditController::class)
        ->name('reviews.edit');
    Route::put('/requests/{requestModel}/review', \App\Http\Controllers\Verificator\Review\UpdateController::class)
        ->name('reviews.update');
    Route::delete('/requests/{requestModel}/review', \App\Http\Controllers\Verificator\Review\DestroyController::class)
        ->name('reviews.destroy');
});

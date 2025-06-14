<?php

use App\Http\Controllers\AssistantVerificationController;
use App\Http\Controllers\FilterController;
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

    Route::get('/requests/filter', FilterController::class)
        ->middleware('can:viewAny,App\Models\RequestModel')
        ->name('requests.filter');

    Route::get('/requests/create', App\Http\Controllers\Verificator\Request\CreateController::class)->name('requests.create');
    Route::post('/requests', App\Http\Controllers\Verificator\Request\StoreController::class)->name('requests.store');
    Route::get('/requests/{request}', App\Http\Controllers\Verificator\Request\ShowController::class)
        ->middleware('can:view,request')
        ->name('requests.show');
    Route::get('/requests/{request}/edit', App\Http\Controllers\Verificator\Request\EditController::class)->name('requests.edit');
    Route::put('/requests/{request}', App\Http\Controllers\Verificator\Request\UpdateController::class)->name('requests.update');
    Route::delete('/requests/{request}', App\Http\Controllers\Verificator\Request\DestroyController::class)->name('requests.destroy');
});

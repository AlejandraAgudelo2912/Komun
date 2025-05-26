<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:god'])->prefix('god')->name('god.')->group(function () {
    Route::get('/dashboard', function () {
        return view('god.dashboard');
    })->name('dashboard');

    Route::get('/categories', App\Http\Controllers\God\Category\IndexController::class)
        ->middleware('can:viewAny,App\Models\Category')
        ->name('categories.index');

    Route::get('/categories/create', App\Http\Controllers\God\Category\CreateController::class)
        ->middleware('can:create,App\Models\Category')
        ->name('categories.create');

    Route::post('/categories', App\Http\Controllers\God\Category\StoreController::class)
        ->middleware('can:create,App\Models\Category')
        ->name('categories.store');

    Route::get('/categories/{category}/edit', App\Http\Controllers\God\Category\EditController::class)
        ->middleware('can:update,category')
        ->name('categories.edit');

    Route::put('/categories/{category}', App\Http\Controllers\God\Category\UpdateController::class)
        ->middleware('can:update,category')
        ->name('categories.update');

    Route::delete('/categories/{category}', App\Http\Controllers\God\Category\DestroyController::class)
        ->middleware('can:delete,category')
        ->name('categories.destroy');

    Route::get('/requests', App\Http\Controllers\God\Request\IndexController::class)
        ->middleware('can:viewAny,App\Models\RequestModel')
        ->name('requests.index');

    Route::get('/requests/create', App\Http\Controllers\God\Request\CreateController::class)
        ->middleware('can:create,App\Models\RequestModel')
        ->name('requests.create');

    Route::post('/requests', App\Http\Controllers\God\Request\StoreController::class)
        ->middleware('can:create,App\Models\RequestModel')
        ->name('requests.store');

    Route::get('/requests/{requestModel}/edit', App\Http\Controllers\God\Request\EditController::class)
        ->middleware('can:update,requestModel')
        ->name('requests.edit');

    Route::put('/requests/{requestModel}', App\Http\Controllers\God\Request\UpdateController::class)
        ->middleware('can:update,requestModel')
        ->name('requests.update');

    Route::delete('/requests/{requestModel}', App\Http\Controllers\God\Request\DestroyController::class)
        ->middleware('can:delete,requestModel')
        ->name('requests.destroy');

});

<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:needHelp'])->prefix('needhelp')->name('needhelp.')->group(function () {
    Route::get('/dashboard', function () {
        return view('needhelp.dashboard');
    })->name('dashboard');

    Route::get('/categories', App\Http\Controllers\NeedHelp\Category\IndexController::class)->name('categories.index');

    //Route::post('/reviews/{request}', [NeedHelp\Review\StoreController::class, '__invoke'])->name('reviews.store');
    //Route::put('/reviews/{review}', [NeedHelp\Review\UpdateController::class, '__invoke'])->name('reviews.update');
    //Route::delete('/reviews/{review}', [NeedHelp\Review\DestroyController::class, '__invoke'])->name('reviews.destroy');

    Route::get('/requests', App\Http\Controllers\NeedHelp\Request\IndexController::class)
        ->middleware('can:viewAny,App\Models\RequestModel')
        ->name('requests.index');

    Route::get('/requests/create', App\Http\Controllers\NeedHelp\Request\CreateController::class)
        ->middleware('can:create,App\Models\RequestModel')
        ->name('requests.create');

    Route::post('/requests', App\Http\Controllers\NeedHelp\Request\StoreController::class)
        ->middleware('can:create,App\Models\RequestModel')
        ->name('requests.store');

    Route::get('/requests/{requestModel}', App\Http\Controllers\NeedHelp\Request\ShowController::class)
        ->middleware('can:view,requestModel')
        ->name('requests.show');

    Route::get('/requests/{requestModel}/edit', App\Http\Controllers\NeedHelp\Request\EditController::class)
        ->middleware('can:update,requestModel')
        ->name('requests.edit');

    Route::put('/requests/{requestModel}', App\Http\Controllers\NeedHelp\Request\UpdateController::class)
        ->middleware('can:update,requestModel')
        ->name('requests.update');

    Route::delete('/requests/{requestModel}', App\Http\Controllers\NeedHelp\Request\DestroyController::class)
        ->middleware('can:delete,requestModel')
        ->name('requests.destroy');

    // Rutas para reseñas
    Route::get('/requests/{requestModel}/review', \App\Http\Controllers\NeedHelp\Review\CreateController::class)
        ->name('reviews.create');
    Route::post('/requests/{requestModel}/review', \App\Http\Controllers\NeedHelp\Review\StoreController::class)
        ->name('reviews.store');
});

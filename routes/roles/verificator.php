<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:verificator'])->prefix('verificator')->name('verificator.')->group(function () {
    Route::get('/dashboard', function () {
        return view('verificator.dashboard');
    })->name('dashboard');

    Route::get('/categories', App\Http\Controllers\Verificator\Category\IndexController::class)->name('categories.index');

    //Route::post('/reviews/{request}', [Verificator\Review\StoreController::class, '__invoke'])->name('reviews.store');
    //Route::put('/reviews/{review}', [Verificator\Review\UpdateController::class, '__invoke'])->name('reviews.update');
    //Route::delete('/reviews/{review}', [Verificator\Review\DestroyController::class, '__invoke'])->name('reviews.destroy');

    Route::get('/requests', App\Http\Controllers\Verificator\Request\IndexController::class)->name('requests.index');
    Route::get('/requests/create', App\Http\Controllers\Verificator\Request\CreateController::class)->name('requests.create');
    Route::post('/requests', App\Http\Controllers\Verificator\Request\StoreController::class)->name('requests.store');
    Route::get('/requests/{request}/edit', App\Http\Controllers\Verificator\Request\EditController::class)->name('requests.edit');
    Route::put('/requests/{request}', App\Http\Controllers\Verificator\Request\UpdateController::class)->name('requests.update');
    Route::delete('/requests/{request}', App\Http\Controllers\Verificator\Request\DestroyController::class)->name('requests.destroy');
});

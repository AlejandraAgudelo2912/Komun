<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:god'])->prefix('god')->name('god.')->group(function () {
    Route::get('/dashboard', function () {
        return view('god.dashboard');
    })->name('dashboard');

    Route::get('/categories', App\Http\Controllers\God\Category\IndexController::class)->name('categories.index');
    Route::get('/categories/create', App\Http\Controllers\God\Category\CreateController::class)->name('categories.create');
    Route::post('/categories', App\Http\Controllers\God\Category\StoreController::class)->name('categories.store');
    Route::get('/categories/{category}/edit', App\Http\Controllers\God\Category\EditController::class)->name('categories.edit');
    Route::put('/categories/{category}', App\Http\Controllers\God\Category\UpdateController::class)->name('categories.update');
    Route::delete('/categories/{category}', App\Http\Controllers\God\Category\DestroyController::class)->name('categories.destroy');

    //Route::post('/reviews/{request}', [God\Review\StoreController::class, '__invoke'])->name('reviews.store');
    //Route::put('/reviews/{review}', [God\Review\UpdateController::class, '__invoke'])->name('reviews.update');
    //Route::delete('/reviews/{review}', [God\Review\DestroyController::class, '__invoke'])->name('reviews.destroy');

    Route::get('/requests', App\Http\Controllers\God\Request\IndexController::class)->name('requests.index');
    Route::get('/requests/create', App\Http\Controllers\God\Request\CreateController::class)->name('requests.create');
    Route::post('/requests', App\Http\Controllers\God\Request\StoreController::class)->name('requests.store');
    Route::get('/requests/{request}/edit', App\Http\Controllers\God\Request\EditController::class)->name('requests.edit');
    Route::put('/requests/{request}', App\Http\Controllers\God\Request\UpdateController::class)->name('requests.update');
    Route::delete('/requests/{request}', App\Http\Controllers\God\Request\DestroyController::class)->name('requests.destroy');
});

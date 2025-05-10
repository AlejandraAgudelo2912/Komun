<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:assistant'])->prefix('assistant')->name('assistant.')->group(function () {
    Route::get('/dashboard', function () {
        return view('assistant.dashboard');
    })->name('dashboard');

    Route::get('/categories', App\Http\Controllers\Assistant\Category\IndexController::class)->name('categories.index');

    Route::post('/reviews/{request}', [Assistant\Review\StoreController::class, '__invoke'])->name('reviews.store');
    Route::put('/reviews/{review}', [Assistant\Review\UpdateController::class, '__invoke'])->name('reviews.update');
    Route::delete('/reviews/{review}', [Assistant\Review\DestroyController::class, '__invoke'])->name('reviews.destroy');

    Route::get('/requests', App\Http\Controllers\Assistant\Request\IndexController::class)->name('requests.index');
    Route::get('/requests/create', App\Http\Controllers\Assistant\Request\CreateController::class)->name('requests.create');
    Route::post('/requests', App\Http\Controllers\Assistant\Request\StoreController::class)->name('requests.store');
    Route::get('/requests/{request}/edit', App\Http\Controllers\Assistant\Request\EditController::class)->name('requests.edit');
    Route::put('/requests/{request}', App\Http\Controllers\Assistant\Request\UpdateController::class)->name('requests.update');
    Route::delete('/requests/{request}', App\Http\Controllers\Assistant\Request\DestroyController::class)->name('requests.destroy');
}); 
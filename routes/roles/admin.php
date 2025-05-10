<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/categories', App\Http\Controllers\Admin\Category\IndexController::class)->name('categories.index');
    Route::get('/categories/create', App\Http\Controllers\Admin\Category\CreateController::class)->name('categories.create');
    Route::post('/categories', App\Http\Controllers\Admin\Category\StoreController::class)->name('categories.store');
    Route::get('/categories/{category}/edit', App\Http\Controllers\Admin\Category\EditController::class)->name('categories.edit');
    Route::put('/categories/{category}', App\Http\Controllers\Admin\Category\UpdateController::class)->name('categories.update');
    Route::delete('/categories/{category}', App\Http\Controllers\Admin\Category\DestroyController::class)->name('categories.destroy');

    Route::post('/reviews/{request}', [Admin\Review\StoreController::class, '__invoke'])->name('reviews.store');
    Route::put('/reviews/{review}', [Admin\Review\UpdateController::class, '__invoke'])->name('reviews.update');
    Route::delete('/reviews/{review}', [Admin\Review\DestroyController::class, '__invoke'])->name('reviews.destroy');

    Route::get('/requests', App\Http\Controllers\Admin\Request\IndexController::class)->name('requests.index');
    Route::get('/requests/create', App\Http\Controllers\Admin\Request\CreateController::class)->name('requests.create');
    Route::post('/requests', App\Http\Controllers\Admin\Request\StoreController::class)->name('requests.store');
    Route::get('/requests/{request}/edit', App\Http\Controllers\Admin\Request\EditController::class)->name('requests.edit');
    Route::put('/requests/{request}', App\Http\Controllers\Admin\Request\UpdateController::class)->name('requests.update');
    Route::delete('/requests/{request}', App\Http\Controllers\Admin\Request\DestroyController::class)->name('requests.destroy');
}); 
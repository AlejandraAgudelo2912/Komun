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
}); 
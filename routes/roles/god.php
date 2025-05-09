<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:god'])->prefix('god')->name('god.')->group(function () {
    Route::get('/dashboard', function () {
        return view('god.dashboard');
    })->name('dashboard');

    Route::get('/categories', App\Http\Controllers\God\Category\IndexController::class)->name('categories.index');
   // Route::get('/categories/create', App\Http\Controllers\God\Category\CreateController::class)->name('categories.create');
    //Route::post('/categories', App\Http\Controllers\God\Category\StoreController::class)->name('categories.store');
    //Route::get('/categories/{category}/edit', App\Http\Controllers\God\Category\EditController::class)->name('categories.edit');
    //Route::put('/categories/{category}', App\Http\Controllers\God\Category\UpdateController::class)->name('categories.update');
    //Route::delete('/categories/{category}', App\Http\Controllers\God\Category\DestroyController::class)->name('categories.destroy');
    
   }); 
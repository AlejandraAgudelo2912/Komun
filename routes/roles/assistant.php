<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:assistant'])->prefix('assistant')->name('assistant.')->group(function () {
    Route::get('/dashboard', function () {
        return view('assistant.dashboard');
    })->name('dashboard');

    Route::get('/categories', App\Http\Controllers\Assistant\Category\IndexController::class)->name('categories.index');
}); 
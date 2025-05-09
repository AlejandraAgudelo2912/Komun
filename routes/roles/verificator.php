<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:verificator'])->prefix('verificator')->name('verificator.')->group(function () {
    Route::get('/dashboard', function () {
        return view('verificator.dashboard');
    })->name('dashboard');

    Route::get('/categories', App\Http\Controllers\Verificator\Category\IndexController::class)->name('categories.index');
}); 
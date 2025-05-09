<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:verificator'])->group(function () {
    Route::get('/verificator/dashboard', function () {
        return view('verificator.dashboard');
    })->name('verificator.dashboard');
    
}); 
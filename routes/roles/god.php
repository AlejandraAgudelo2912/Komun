<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:god'])->group(function () {
    Route::get('/god/dashboard', function () {
        return view('god.dashboard');
    })->name('god.dashboard');
    
}); 
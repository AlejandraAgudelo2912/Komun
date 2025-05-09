<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:assistant'])->group(function () {
    Route::get('/assistant/dashboard', function () {
        return view('assistant.dashboard');
    })->name('assistant.dashboard');
    
}); 
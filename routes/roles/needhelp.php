<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:needHelp'])->group(function () {
    Route::get('/needhelp/dashboard', function () {
        return view('needhelp.dashboard');
    })->name('needhelp.dashboard');
    

}); 
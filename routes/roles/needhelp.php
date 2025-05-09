<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:needHelp'])->prefix('needhelp')->name('needhelp.')->group(function () {
    Route::get('/dashboard', function () {
        return view('needhelp.dashboard');
    })->name('dashboard');

    Route::get('/categories', App\Http\Controllers\NeedHelp\Category\IndexController::class)->name('categories.index');
}); 
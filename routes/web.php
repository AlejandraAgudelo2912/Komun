<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'role:god'])->group(function () {
    Route::get('/god/dashboard', function () {
        return view('god.dashboard');
    })->name('god.dashboard');
});

Route::middleware(['auth', 'role:verificator'])->group(function () {
    Route::get('/verificator/dashboard', function () {
        return view('verificator.dashboard');
    })->name('verificator.dashboard');
});

Route::middleware(['auth', 'role:assistant'])->group(function () {
    Route::get('/assistant/dashboard', function () {
        return view('assistant.dashboard');
    })->name('assistant.dashboard');
});

Route::middleware(['auth', 'role:needHelp'])->group(function () {
    Route::get('/needhelp/dashboard', function () {
        return view('needhelp.dashboard');
    })->name('needhelp.dashboard');
});

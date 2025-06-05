<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/quiero-ayudar', App\Http\Controllers\AssistantFormController::class)->name('assistant.form');
    Route::post('/quiero-ayudar', App\Http\Controllers\AssistantStoreController::class)->name('assistant.store');

    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('god')) {
            return redirect()->route('god.dashboard');
        } elseif ($user->hasRole('verificator')) {
            return redirect()->route('verificator.dashboard');
        } elseif ($user->hasRole('assistant')) {
            return redirect()->route('assistant.dashboard');
        } elseif ($user->hasRole('needHelp')) {
            return redirect()->route('needhelp.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');

    // Ruta para generar PDF de estadísticas de usuario
    Route::get('/user/{user}/stats', [PdfController::class, 'userStats'])
        ->name('user.stats.pdf');
});

require __DIR__.'/roles/admin.php';
require __DIR__.'/roles/god.php';
require __DIR__.'/roles/verificator.php';
require __DIR__.'/roles/assistant.php';
require __DIR__.'/roles/needhelp.php';

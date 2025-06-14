<?php

use App\Http\Controllers\CategoryFollowController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/want-to-help', App\Http\Controllers\AssistantFormController::class)->name('assistant.form');
    Route::post('/want-to-help', App\Http\Controllers\AssistantStoreController::class)->name('assistant.store');

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

    Route::post('/categories/{category}/follow', [CategoryFollowController::class, 'follow'])->name('categories.follow');
    Route::delete('/categories/{category}/follow', [CategoryFollowController::class, 'unfollow'])->name('categories.unfollow');
    Route::get('/categories/followed', [CategoryFollowController::class, 'followedCategories'])->name('categories.followed');

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/user/{user}/stats', [PdfController::class, 'userStats'])
            ->name('user.stats.pdf');
    });

    Route::middleware(['auth', 'role:god'])->group(function () {
        Route::get('/user/{user}/stats', [PdfController::class, 'userStats'])
            ->name('user.stats.pdf.god');
    });

});

require __DIR__.'/roles/admin.php';
require __DIR__.'/roles/god.php';
require __DIR__.'/roles/verificator.php';
require __DIR__.'/roles/assistant.php';
require __DIR__.'/roles/needhelp.php';

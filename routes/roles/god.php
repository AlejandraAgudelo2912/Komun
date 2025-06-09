<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:god'])->prefix('god')->name('god.')->group(function () {
    Route::get('/dashboard', App\Http\Controllers\God\DashboardController::class)->name('dashboard');

    Route::get('/profiles', App\Http\Controllers\God\Profile\IndexController::class)->name('profiles.index');
    Route::get('/profiles/{user}/edit', App\Http\Controllers\God\Profile\EditController::class)->name('profiles.edit');
    Route::put('/profiles/{user}', App\Http\Controllers\God\Profile\UpdateController::class)->name('profiles.update');
    Route::delete('/profiles/{user}', App\Http\Controllers\God\Profile\DeleteController::class)->name('profiles.delete');
    Route::get('/profiles/pdf', [App\Http\Controllers\PdfController::class, 'usersList'])->name('profiles.pdf');

    Route::get('/categories', App\Http\Controllers\God\Category\IndexController::class)
        ->middleware('can:viewAny,App\Models\Category')
        ->name('categories.index');

    Route::get('/categories/create', App\Http\Controllers\God\Category\CreateController::class)
        ->middleware('can:create,App\Models\Category')
        ->name('categories.create');

    Route::post('/categories', App\Http\Controllers\God\Category\StoreController::class)
        ->middleware('can:create,App\Models\Category')
        ->name('categories.store');

    Route::get('/categories/{category}/edit', App\Http\Controllers\God\Category\EditController::class)
        ->middleware('can:update,category')
        ->name('categories.edit');

    Route::put('/categories/{category}', App\Http\Controllers\God\Category\UpdateController::class)
        ->middleware('can:update,category')
        ->name('categories.update');

    Route::delete('/categories/{category}', App\Http\Controllers\God\Category\DestroyController::class)
        ->middleware('can:delete,category')
        ->name('categories.destroy');

    Route::get('/categories/{category}', App\Http\Controllers\God\Category\ShowController::class)
        ->middleware('can:view,category')
        ->name('categories.show');

    Route::get('/requests', App\Http\Controllers\God\Request\IndexController::class)
        ->middleware('can:viewAny,App\Models\RequestModel')
        ->name('requests.index');

    Route::get('/requests/filter', App\Http\Controllers\FilterController::class)
        ->middleware('can:viewAny,App\Models\RequestModel')
        ->name('requests.filter');

    Route::get('/requests/{requestModel}', App\Http\Controllers\God\Request\ShowController::class)
        ->middleware('can:view,requestModel')
        ->name('requests.show');

    Route::get('/requests/create', App\Http\Controllers\God\Request\CreateController::class)
        ->middleware('can:create,App\Models\RequestModel')
        ->name('requests.create');

    Route::post('/requests', App\Http\Controllers\God\Request\StoreController::class)
        ->middleware('can:create,App\Models\RequestModel')
        ->name('requests.store');

    Route::get('/requests/{requestModel}/edit', App\Http\Controllers\God\Request\EditController::class)
        ->middleware('can:update,requestModel')
        ->name('requests.edit');

    Route::put('/requests/{requestModel}', App\Http\Controllers\God\Request\UpdateController::class)
        ->middleware('can:update,requestModel')
        ->name('requests.update');

    Route::delete('/requests/{requestModel}', App\Http\Controllers\God\Request\DestroyController::class)
        ->middleware('can:delete,requestModel')
        ->name('requests.destroy');

});

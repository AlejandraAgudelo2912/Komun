<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:assistant'])->prefix('assistant')->name('assistant.')->group(function () {
    Route::get('/dashboard', App\Http\Controllers\Assistant\DashboardController::class)->name('dashboard');

    Route::get('/requests', App\Http\Controllers\Assistant\Request\IndexController::class)
        ->middleware('can:viewAny,App\Models\RequestModel')
        ->name('requests.index');

    Route::get('/requests/filter', [App\Http\Controllers\FilterController::class, 'filter'])
        ->middleware('can:viewAny,App\Models\RequestModel')
        ->name('requests.filter');

    Route::get('/requests/{requestModel}', App\Http\Controllers\Assistant\Request\ShowController::class)
        ->middleware('can:view,requestModel')
        ->name('requests.show');

    Route::get('/requests/{requestModel}/apply', App\Http\Controllers\AplyRequestController::class)
        ->middleware('can:apply,requestModel')
        ->name('requests.apply');

    Route::post('/requests/{requestModel}/apply', App\Http\Controllers\SaveAplyRequestController::class)
        ->middleware('can:apply,requestModel')
        ->name('requests.apply.save');

    Route::get('/categories', App\Http\Controllers\Assistant\Category\IndexController::class)
        ->middleware('can:viewAny,App\Models\Category')
        ->name('categories.index');

    Route::get('/requests/create', App\Http\Controllers\Assistant\Request\CreateController::class)->name('requests.create');
    Route::post('/requests', App\Http\Controllers\Assistant\Request\StoreController::class)->name('requests.store');
    Route::get('/requests/{requestModel}/edit', App\Http\Controllers\Assistant\Request\EditController::class)->name('requests.edit');
    Route::put('/requests/{requestModel}', App\Http\Controllers\Assistant\Request\UpdateController::class)->name('requests.update');
    Route::delete('/requests/{requestModel}', App\Http\Controllers\Assistant\Request\DestroyController::class)->name('requests.destroy');
});

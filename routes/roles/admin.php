<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');

    Route::get('/profiles', App\Http\Controllers\Admin\Profile\IndexController::class)->name('profiles.index');
    Route::get('/profiles/pdf', [App\Http\Controllers\PdfController::class, 'usersList'])->name('profiles.pdf');

    Route::get('/categories', App\Http\Controllers\Admin\Category\IndexController::class)
        ->middleware('can:viewAny,App\Models\Category')
        ->name('categories.index');

    Route::get('/categories/create', App\Http\Controllers\Admin\Category\CreateController::class)
        ->middleware('can:create,App\Models\Category')
        ->name('categories.create');

    Route::post('/categories', App\Http\Controllers\Admin\Category\StoreController::class)
        ->middleware('can:create,App\Models\Category')
        ->name('categories.store');

    Route::get('/categories/{category}/edit', App\Http\Controllers\Admin\Category\EditController::class)
        ->middleware('can:update,category')
        ->name('categories.edit');

    Route::put('/categories/{category}', App\Http\Controllers\Admin\Category\UpdateController::class)
        ->middleware('can:update,category')
        ->name('categories.update');

    Route::delete('/categories/{category}', App\Http\Controllers\Admin\Category\DestroyController::class)
        ->middleware('can:delete,category')
        ->name('categories.destroy');

    Route::get('/categories/{category}', App\Http\Controllers\Admin\Category\ShowController::class)
        ->middleware('can:view,category')
        ->name('categories.show');

    Route::get('/requests', App\Http\Controllers\Admin\Request\IndexController::class)
        ->middleware('can:viewAny,App\Models\RequestModel')
        ->name('requests.index');

    Route::get('/requests/filter', [App\Http\Controllers\FilterController::class, 'filter'])
        ->middleware('can:viewAny,App\Models\RequestModel')
        ->name('requests.filter');

    Route::get('/requests/create', App\Http\Controllers\Admin\Request\CreateController::class)
        ->middleware('can:create,App\Models\RequestModel')
        ->name('requests.create');

    Route::post('/requests', App\Http\Controllers\Admin\Request\StoreController::class)
        ->middleware('can:create,App\Models\RequestModel')
        ->name('requests.store');

    Route::get('/requests/{requestModel}/edit', App\Http\Controllers\Admin\Request\EditController::class)
        ->middleware('can:update,requestModel')
        ->name('requests.edit');

    Route::put('/requests/{requestModel}', App\Http\Controllers\Admin\Request\UpdateController::class)
        ->middleware('can:update,requestModel')
        ->name('requests.update');

    Route::delete('/requests/{requestModel}', App\Http\Controllers\Admin\Request\DestroyController::class)
        ->middleware('can:delete,requestModel')
        ->name('requests.destroy');

    Route::get('/requests/{requestModel}', App\Http\Controllers\Admin\Request\ShowController::class)
        ->middleware('can:view,requestModel')
        ->name('requests.show');

    Route::delete('/reviews/{review}', \App\Http\Controllers\Admin\Review\DestroyController::class)
        ->name('reviews.destroy');

});

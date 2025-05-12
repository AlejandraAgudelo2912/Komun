<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:needHelp'])->prefix('needhelp')->name('needhelp.')->group(function () {
    Route::get('/dashboard', function () {
        return view('needhelp.dashboard');
    })->name('dashboard');

    Route::get('/categories', App\Http\Controllers\NeedHelp\Category\IndexController::class)->name('categories.index');

    //Route::post('/reviews/{request}', [NeedHelp\Review\StoreController::class, '__invoke'])->name('reviews.store');
    //Route::put('/reviews/{review}', [NeedHelp\Review\UpdateController::class, '__invoke'])->name('reviews.update');
    //Route::delete('/reviews/{review}', [NeedHelp\Review\DestroyController::class, '__invoke'])->name('reviews.destroy');

    Route::get('/requests', App\Http\Controllers\NeedHelp\Request\IndexController::class)->name('requests.index');
    Route::get('/requests/create', App\Http\Controllers\NeedHelp\Request\CreateController::class)->name('requests.create');
    Route::post('/requests', App\Http\Controllers\NeedHelp\Request\StoreController::class)->name('requests.store');
    Route::get('/requests/{request}', App\Http\Controllers\NeedHelp\Request\ShowController::class)->name('requests.show');
    Route::get('/requests/{request}/edit', App\Http\Controllers\NeedHelp\Request\EditController::class)->name('requests.edit');
    Route::put('/requests/{request}', App\Http\Controllers\NeedHelp\Request\UpdateController::class)->name('requests.update');
    Route::delete('/requests/{request}', App\Http\Controllers\NeedHelp\Request\DestroyController::class)->name('requests.destroy');
});

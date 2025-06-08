<?php

namespace App\Providers;

use App\Models\RequestModel;
use App\Observers\RequestModelObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        RequestModel::observe(RequestModelObserver::class);
    }
}

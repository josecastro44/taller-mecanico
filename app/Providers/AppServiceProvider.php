<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Use the application environment helper instead of env() at runtime.
        // This is more reliable when config is cached.
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}

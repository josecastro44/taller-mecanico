<?php

namespace App\Providers;

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
<<<<<<< HEAD
    public function boot(): void
    {
        //
=======
public function boot(): void
    {
        if (env('APP_ENV') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
>>>>>>> 156b6413b10d4111281be8189e0193873341bebb
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // Importul necesar pentru gestionarea URL-urilor

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
        // Forțează framework-ul să genereze link-uri securizate (HTTPS) pe serverul Render.com
        if (app()->environment('production') || app()->isProduction()) {
            URL::forceScheme('https');
        }
    }
}

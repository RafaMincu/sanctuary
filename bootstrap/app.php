<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Artisan; // IMPORT OBLIGATORIU SUS!

// Forțăm rularea migrărilor în fundal dacă suntem pe serverul live
if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'sanctuaryhub.ro') {
    try {
        Artisan::call('migrate', ['--force' => true]);
    } catch (\Exception $e) {
        // Ignorăm erorile dacă tabelele există deja
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

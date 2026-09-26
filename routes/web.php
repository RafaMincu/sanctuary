<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Codul de migrare automată la prima accesare a site-ului
try {
    Artisan::call('migrate', ['--force' => true]);
} catch (\Exception $e) {
    // Ignoră dacă tabelele au fost deja create
}

Route::get('/', function () {
    return view('welcome', ['page' => 'home']);
});

Route::get('/garaj', function () {
    return view('garaj', ['page' => 'garaj']);
});

Route::get('/fitness', function () {
    return view('fitness', ['page' => 'fitness']);
});

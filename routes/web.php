<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', ['page' => 'home']);
});

Route::get('/garaj', function () {
    return view('garaj', ['page' => 'garaj']);
});

Route::get('/fitness', function () {
    return view('fitness', ['page' => 'fitness']);
});

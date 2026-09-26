<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/garaj', function () {
    return view('garaj');
})->name('garaj');

Route::get('/fitness', function () {
    return view('fitness');
})->name('fitness');

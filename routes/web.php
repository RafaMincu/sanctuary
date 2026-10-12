<?php

use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;

// Live chat API routes (public – works for guests via session + authenticated users)
Route::get('/api/chat/messages', [ChatController::class, 'index'])->name('chat.messages');
Route::post('/api/chat/message', [ChatController::class, 'store'])->name('chat.message.store');

// Admin chat panel (protected by session-based AdminAuth middleware)
Route::get('/admin/chat/login', [AdminChatController::class, 'loginForm'])->name('admin.login');
Route::post('/admin/chat/login', [AdminChatController::class, 'login'])->name('admin.chat.login');
Route::post('/admin/chat/logout', [AdminChatController::class, 'logout'])->name('admin.chat.logout');
Route::middleware('admin')->group(function () {
    Route::get('/admin/chat', [AdminChatController::class, 'index'])->name('admin.chat.index');
    // NOTĂ: trebuie înregistrat ÎNAINTE de /admin/chat/{sessionId},
    // altfel "conversations" ar fi tratat ca un session ID.
    Route::get('/admin/chat/conversations', [AdminChatController::class, 'conversations'])->name('admin.chat.conversations');
    Route::get('/admin/chat/{sessionId}/mesaje', [AdminChatController::class, 'messages'])->name('admin.chat.messages');
    Route::get('/admin/chat/{sessionId}', [AdminChatController::class, 'show'])->name('admin.chat.show');
    Route::post('/admin/chat/{sessionId}/reply', [AdminChatController::class, 'reply'])->name('admin.chat.reply');
});

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/garaj', function () {
    return view('garaj');
})->name('garaj');

Route::get('/food', function () {
    return view('food');
})->name('food');

Route::get('/fitness', function () {
    return view('fitness');
})->name('fitness');

// Conturi pentru clienți.
Route::middleware('guest')->group(function () {
    Route::get('/autentificare', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/autentificare', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.store');
    Route::get('/inregistrare', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inregistrare', [AuthController::class, 'register'])->middleware('throttle:6,1')->name('register.store');

    // Autentificare cu Google (OAuth 2.0 prin Laravel Socialite).
    Route::get('/autentificare/google', [GoogleAuthController::class, 'redirect'])->middleware('throttle:10,1')->name('google.redirect');
    Route::get('/autentificare/google/callback', [GoogleAuthController::class, 'callback'])->middleware('throttle:10,1')->name('google.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/contul-meu', [AuthController::class, 'dashboard'])->name('account.dashboard');
    // Alias fără nume pentru fluxul de login Google (redirecționează către /dashboard).
    Route::get('/dashboard', [AuthController::class, 'dashboard']);
    Route::post('/deconectare', [AuthController::class, 'logout'])->name('logout');
});

// Sanctuary Unified Bookings System
Route::get('/programari', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/programari', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/programari/verificare', [BookingController::class, 'lookup'])->name('bookings.lookup');
Route::middleware('admin')->group(function () {
    Route::get('/programari/administrare', [BookingController::class, 'index'])->name('bookings.index');
    Route::patch('/programari/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
});
Route::get('/programari/{booking_number}', [BookingController::class, 'show'])->name('bookings.show');

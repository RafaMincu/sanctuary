<?php

use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;



use Illuminate\Support\Facades\Artisan;

// Rută temporară pentru a ocoli complet blocajele de pe Render Free
Route::get('/executa-tot-baza-date', function () {
    try {
        // 1. Curățăm cache-ul Laravel ca să fim siguri că nu e nimic blocat
        Artisan::call('route:clear');
        Artisan::call('config:clear');

        // 2. Forțăm rularea migrărilor (crearea tabelelor lipsă precum 'admins')
        Artisan::call('migrate', ['--force' => true]);

        // 3. Forțăm rularea seeder-ului pentru a introduce contul de admin criptat
        Artisan::call('db:seed', ['--class' => 'AdminSeeder', '--force' => true]);

        return '✅ REUȘITĂ! Tabela admins a fost creată și adminul a fost injectat în baza de date PostgreSQL de pe Render!';
    } catch (\Exception $e) {
        return '🔴 Eroare la executare: ' . $e->getMessage();
    }
});


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

// Sanctuary Unified Bookings System
Route::get('/programari', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/programari', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/programari/verificare', [BookingController::class, 'lookup'])->name('bookings.lookup');
Route::get('/programari/administrare', [BookingController::class, 'index'])->name('bookings.index');
Route::patch('/programari/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
Route::get('/programari/{booking_number}', [BookingController::class, 'show'])->name('bookings.show');

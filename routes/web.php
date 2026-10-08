<?php

use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;


use App\Models\Admin;

Route::get('/vezi-admin-live', function () {
    // Căutăm primul admin creat în baza de date
    $admin = Admin::first();

    if (!$admin) {
        return "❌ Nu există niciun cont în tabela 'admins' pe serverul Render!";
    }

    return "<h3>Cont găsit în baza live:</h3>
            Nume: <b>{$admin->name}</b><br>
            Email: <b>{$admin->email}</b><br>
            Parolă în baza de date (Hash criptat): <b>{$admin->password}</b><br><br>
            <i>Notă: Dacă parola nu începe cu '$2y$' sau '$2b$', înseamnă că NU este criptată, iar Laravel nu te va lăsa să te loghezi!</i>";
});


use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

Route::get('/migreaza-admin-acum', function () {
    try {
        // Ștergem înregistrarea veche cu parola text simplu
        Admin::where('email', 'admin@sanctuary.ro')->delete();

        // Creăm contul nou și forțăm generarea algoritmului Bcrypt prin Hash::make
        $admin = Admin::create([
            'name'     => 'Admin Sanctuary',
            'email'    => 'admin@sanctuary.ro',
            'password' => Hash::make('test1234'),
        ]);

        return '✅ SUCCES! Parola a fost criptată cu succes prin Bcrypt. Te poți loga!';
    } catch (\Exception $e) {
        return 'Eroare: ' . $e->getMessage();
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

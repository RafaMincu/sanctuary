<?php

use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Models\Admin;

Route::get('/migreaza-admin-acum', function () {
    try {
        // 1. Rulăm DOAR introducerea datelor (seeder-ul), deoarece tabelele există deja
        Artisan::call('db:seed', ['--class' => 'AdminSeeder', '--force' => true]);
        
        // 2. Căutăm administratorul în baza de date ca să verificăm dacă s-a inserat
        $admin = Admin::where('email', 'admin@sanctuary.ro')->first();
        
        if ($admin) {
            return "✅ SUCCES COMPLET! Administratorul a fost salvat și verificat direct în baza online.<br>
                    Nume: {$admin->name}<br>
                    Email: {$admin->email}";
        }
        
        return "⚠️ Seeder-ul a rulat, dar contul NU a fost găsit. Verifică valorile implicite din fișierul AdminSeeder.php.";

    } catch (\Exception $e) {
        return '🔴 Eroare la populare: ' . $e->getMessage();
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

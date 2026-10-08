<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Creează primul cont de admin (idempotent).
     *
     * Configurabil prin .env:
     *   ADMIN_NAME=Ana
     *   ADMIN_EMAIL=ana@sanctuary.ro
     *   ADMIN_CHAT_PASSWORD=parola (aceeași ca pentru panoul vechi)
     *
     * Admini suplimentari se adaugă cu:
     *   php artisan admin:create "Andrei" andrei@sanctuary.ro parola
     */
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@sanctuary.ro')],
            [
                'name'     => env('ADMIN_NAME', 'Admin'),
                'password' => env('ADMIN_CHAT_PASSWORD', 'test'),
            ]
        );
    }
}

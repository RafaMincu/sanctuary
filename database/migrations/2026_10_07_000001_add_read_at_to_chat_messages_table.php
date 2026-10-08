<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adaugă tracking-ul „citit" pentru live chat: fiecare mesaj știe când a fost
     * văzut de cealaltă parte (guest → admin și invers), pentru badge-uri de
     * mesaje necitite și read-receipts (✓ / ✓✓).
     */
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->timestamp('read_at')->nullable();
        });

        // Mesajele existente sunt considerate citite, ca panourile să pornească
        // fără badge-uri „necitite" moștenite.
        DB::table('chat_messages')->whereNull('read_at')->update(['read_at' => DB::raw('created_at')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn('read_at');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number', 32)->unique()->index();
            $table->string('wing', 20)->index(); // 'auto', 'food', 'fitness'
            $table->string('service_type', 100);
            $table->string('client_name', 150);
            $table->string('client_email', 150);
            $table->string('client_phone', 50);
            $table->dateTime('scheduled_at');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->string('status', 30)->default('pending')->index(); // 'pending', 'confirmed', 'completed', 'cancelled'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

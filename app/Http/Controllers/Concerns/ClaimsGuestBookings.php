<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Booking;
use App\Models\User;

/**
 * Preia programările făcute de un vizitator (fără cont) pentru contul
 * care se autentifică. Folosit atât de autentificarea clasică (email +
 * parolă), cât și de cea prin Google, ca să nu existe două copii.
 */
trait ClaimsGuestBookings
{
    /**
     * Atașează contului programările făcute anterior ca vizitator, exclusiv când
     * emailul coincide și programarea nu aparține deja unui alt utilizator.
     */
    private function claimGuestBookings(User $user): void
    {
        Booking::query()
            ->whereNull('user_id')
            ->whereRaw('LOWER(client_email) = ?', [strtolower(trim($user->email))])
            ->update(['user_id' => $user->id]);
    }
}

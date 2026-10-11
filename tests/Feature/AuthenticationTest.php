<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register_and_is_authenticated(): void
    {
        $response = $this->post('/inregistrare', [
            'name' => 'Radu Popescu',
            'email' => 'radu@example.com',
            'password' => 'Parola123',
            'password_confirmation' => 'Parola123',
        ]);

        $response->assertRedirect(route('account.dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'radu@example.com']);
    }

    public function test_registration_claims_guest_bookings_with_the_same_email(): void
    {
        $guestBooking = Booking::create([
            'booking_number' => 'SNC-GUEST1',
            'wing' => 'auto',
            'service_type' => 'revizie',
            'client_name' => 'Radu Popescu',
            'client_email' => 'RADU@EXAMPLE.COM',
            'client_phone' => '0711111111',
            'scheduled_at' => now()->addDays(2),
            'status' => 'pending',
        ]);

        $this->post('/inregistrare', [
            'name' => 'Radu Popescu',
            'email' => 'radu@example.com',
            'password' => 'Parola123',
            'password_confirmation' => 'Parola123',
        ])->assertRedirect(route('account.dashboard'));

        $this->assertDatabaseHas('bookings', [
            'id' => $guestBooking->id,
            'user_id' => User::where('email', 'radu@example.com')->value('id'),
        ]);
    }

    public function test_registered_client_can_login_and_view_only_own_bookings(): void
    {
        $client = User::factory()->create(['email' => 'client@example.com']);
        $otherClient = User::factory()->create();

        Booking::create([
            'user_id' => $client->id,
            'booking_number' => 'SNC-MINE01',
            'wing' => 'auto',
            'service_type' => 'revizie',
            'client_name' => $client->name,
            'client_email' => $client->email,
            'client_phone' => '0711111111',
            'scheduled_at' => now()->addDays(2),
            'status' => 'pending',
        ]);
        Booking::create([
            'user_id' => $otherClient->id,
            'booking_number' => 'SNC-OTHER1',
            'wing' => 'food',
            'service_type' => 'terasa',
            'client_name' => $otherClient->name,
            'client_email' => $otherClient->email,
            'client_phone' => '0722222222',
            'scheduled_at' => now()->addDays(3),
            'status' => 'pending',
        ]);

        $this->post('/autentificare', [
            'email' => 'client@example.com',
            'password' => 'password',
        ])->assertRedirect(route('account.dashboard'));

        $this->get('/contul-meu')
            ->assertOk()
            ->assertSee('SNC-MINE01')
            ->assertDontSee('SNC-OTHER1');
    }

    public function test_login_claims_only_unassigned_bookings_with_the_same_email(): void
    {
        $client = User::factory()->create(['email' => 'client@example.com']);
        $otherClient = User::factory()->create();

        $claimable = Booking::create([
            'booking_number' => 'SNC-CLAIM1',
            'wing' => 'food',
            'service_type' => 'masa_restaurant',
            'client_name' => 'Client',
            'client_email' => 'client@example.com',
            'client_phone' => '0711111111',
            'scheduled_at' => now()->addDays(2),
            'status' => 'pending',
        ]);
        $alreadyAssigned = Booking::create([
            'user_id' => $otherClient->id,
            'booking_number' => 'SNC-KEEP01',
            'wing' => 'food',
            'service_type' => 'masa_restaurant',
            'client_name' => 'Alt Client',
            'client_email' => 'client@example.com',
            'client_phone' => '0722222222',
            'scheduled_at' => now()->addDays(3),
            'status' => 'pending',
        ]);

        $this->post('/autentificare', [
            'email' => 'client@example.com',
            'password' => 'password',
        ])->assertRedirect(route('account.dashboard'));

        $this->assertDatabaseHas('bookings', ['id' => $claimable->id, 'user_id' => $client->id]);
        $this->assertDatabaseHas('bookings', ['id' => $alreadyAssigned->id, 'user_id' => $otherClient->id]);
    }

    public function test_guest_cannot_access_account_dashboard(): void
    {
        $this->get('/contul-meu')->assertRedirect(route('login'));
    }
}
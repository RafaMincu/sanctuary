<?php

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_page_renders_successfully(): void
    {
        $response = $this->get('/programari');

        $response->assertStatus(200);
        $response->assertSee('Programare');
        $response->assertSee('Garaj Auto');
        $response->assertSee('Zona Food');
        $response->assertSee('Sală Fitness');
        $response->assertSee('Transmite Programarea');
    }

    public function test_booking_page_preselects_wing_from_query_parameter(): void
    {
        $response = $this->get('/programari?wing=food');

        $response->assertStatus(200);
        $response->assertSee('value="food"', false);
    }

    public function test_client_can_create_auto_booking(): void
    {
        $payload = [
            'wing' => 'auto',
            'service_type' => 'diagnoza',
            'client_name' => 'Mihai Ionescu',
            'client_email' => 'mihai@example.com',
            'client_phone' => '0712345678',
            'scheduled_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'notes' => 'Martor check engine aprins în bord.',
            'metadata' => [
                'vehicle_make' => 'BMW',
                'vehicle_model' => 'M3 E92 (2011)',
                'vehicle_plate' => 'B-777-SNC',
            ],
        ];

        $response = $this->post('/programari', $payload);

        $booking = Booking::first();
        $this->assertNotNull($booking);
        $this->assertEquals('auto', $booking->wing);
        $this->assertEquals('Mihai Ionescu', $booking->client_name);
        $this->assertEquals('BMW', $booking->metadata['vehicle_make']);
        $this->assertEquals('pending', $booking->status);

        $response->assertRedirect('/programari/' . $booking->booking_number);
        $response->assertSessionHas('success');

        // Check show page renders details
        $showResponse = $this->get('/programari/' . $booking->booking_number);
        $showResponse->assertStatus(200);
        $showResponse->assertSee($booking->booking_number);
        $showResponse->assertSee('Mihai Ionescu');
        $showResponse->assertSee('În Așteptare');
    }

    public function test_client_can_create_food_booking(): void
    {
        $payload = [
            'wing' => 'food',
            'service_type' => 'masa_restaurant',
            'client_name' => 'Elena Radu',
            'client_email' => 'elena@example.com',
            'client_phone' => '0722334455',
            'scheduled_at' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'notes' => 'Masă lângă fereastră dacă este disponibilă.',
            'metadata' => [
                'guests_count' => '4',
                'seating_area' => 'terasa',
            ],
        ];

        $response = $this->post('/programari', $payload);

        $booking = Booking::where('client_email', 'elena@example.com')->first();
        $this->assertNotNull($booking);
        $this->assertEquals('food', $booking->wing);
        $this->assertEquals('terasa', $booking->metadata['seating_area']);

        $response->assertRedirect('/programari/' . $booking->booking_number);
    }

    public function test_client_can_create_fitness_booking(): void
    {
        $payload = [
            'wing' => 'fitness',
            'service_type' => 'antrenor_personal',
            'client_name' => 'Dan Marinescu',
            'client_email' => 'dan@example.com',
            'client_phone' => '0733445566',
            'scheduled_at' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'notes' => 'Obiectiv creștere masă musculară.',
            'metadata' => [
                'fitness_goal' => 'masa',
                'experience_level' => 'intermediar',
            ],
        ];

        $response = $this->post('/programari', $payload);

        $booking = Booking::where('client_email', 'dan@example.com')->first();
        $this->assertNotNull($booking);
        $this->assertEquals('fitness', $booking->wing);
        $this->assertEquals('masa', $booking->metadata['fitness_goal']);

        $response->assertRedirect('/programari/' . $booking->booking_number);
    }

    public function test_booking_validation_fails_with_invalid_data(): void
    {
        $response = $this->post('/programari', [
            'wing' => 'invalid_wing',
            'client_name' => '',
            'client_email' => 'not-an-email',
            'client_phone' => '',
            'scheduled_at' => now()->subDay()->format('Y-m-d H:i:s'), // in the past
        ]);

        $response->assertSessionHasErrors(['wing', 'client_name', 'client_email', 'client_phone', 'scheduled_at']);
        $this->assertEquals(0, Booking::count());
    }

    public function test_client_can_lookup_booking_by_code(): void
    {
        $booking = Booking::create([
            'booking_number' => 'SNC-TEST01',
            'wing' => 'auto',
            'service_type' => 'revizie',
            'client_name' => 'Cristian Popa',
            'client_email' => 'cristian@example.com',
            'client_phone' => '0744556677',
            'scheduled_at' => now()->addDays(4),
            'status' => 'confirmed',
        ]);

        $response = $this->get('/programari/verificare?code=SNC-TEST01');

        $response->assertStatus(200);
        $response->assertSee('SNC-TEST01');
        $response->assertSee('Cristian Popa');
        $response->assertSee('Confirmată');
    }

    public function test_staff_can_view_bookings_dashboard(): void
    {
        Booking::create([
            'booking_number' => 'SNC-DASH01',
            'wing' => 'fitness',
            'service_type' => 'inscriere_club',
            'client_name' => 'Ana Stan',
            'client_email' => 'ana@example.com',
            'client_phone' => '0755667788',
            'scheduled_at' => now()->addDays(5),
            'status' => 'pending',
        ]);

        $response = $this->get('/programari/administrare');

        $response->assertStatus(200);
        $response->assertSee('Gestiune Programări');
        $response->assertSee('SNC-DASH01');
        $response->assertSee('Ana Stan');
    }

    public function test_staff_can_update_booking_status(): void
    {
        $booking = Booking::create([
            'booking_number' => 'SNC-STAT01',
            'wing' => 'food',
            'service_type' => 'eveniment',
            'client_name' => 'Radu Enache',
            'client_email' => 'radu@example.com',
            'client_phone' => '0766778899',
            'scheduled_at' => now()->addDays(2),
            'status' => 'pending',
        ]);

        $response = $this->patch("/programari/{$booking->id}/status", [
            'status' => 'confirmed',
        ]);

        $response->assertSessionHas('success');
        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status);
    }
}

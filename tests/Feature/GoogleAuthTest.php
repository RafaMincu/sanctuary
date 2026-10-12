<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Credențiale OAuth de test — niciodată valori reale.
        config([
            'services.google.client_id' => 'test-client-id.apps.googleusercontent.com',
            'services.google.client_secret' => 'test-client-secret',
            'services.google.redirect' => 'http://localhost/autentificare/google/callback',
        ]);
    }

    // ─── Redirecționarea către Google ─────────────────────────────────────

    public function test_google_redirect_sends_user_to_google_with_state(): void
    {
        $response = $this->get('/autentificare/google');

        $response->assertRedirect();
        $location = (string) $response->headers->get('Location');

        $this->assertStringContainsString('accounts.google.com', $location);
        $this->assertStringContainsString('test-client-id.apps.googleusercontent.com', $location);
        $response->assertSessionHas('state');
    }

    // ─── Callback: utilizator nou ─────────────────────────────────────────

    public function test_google_callback_creates_new_user_and_authenticates(): void
    {
        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-uid-new',
            'name' => 'Ion Google',
            'email' => 'ion.google@gmail.com',
        ]));

        $response = $this->get('/autentificare/google/callback');

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $user = User::where('email', 'ion.google@gmail.com')->firstOrFail();
        $this->assertSame('google-uid-new', $user->google_id);
        $this->assertNotNull($user->email_verified_at, 'Emailul Google trebuie marcat drept verificat.');
        $this->assertTrue(Hash::isHashed($user->password), 'Parola nouă trebuie generată și hash-uită (bcrypt).');
        $this->assertStringStartsWith('$2y$', $user->password, 'Parola trebuie hash-uită cu bcrypt.');
    }

    public function test_google_callback_creates_user_with_fallback_name_when_google_name_is_empty(): void
    {
        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-uid-noname',
            'name' => '',
            'email' => 'faranume@gmail.com',
        ]));

        $this->get('/autentificare/google/callback')->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'faranume@gmail.com',
            'name' => 'faranume@gmail.com',
        ]);
    }

    // ─── Callback: utilizator existent ────────────────────────────────────

    public function test_google_callback_authenticates_existing_google_user_without_creating_duplicate(): void
    {
        $existing = User::factory()->create([
            'email' => 'loyal@example.com',
            'google_id' => 'google-uid-loyal',
        ]);

        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-uid-loyal',
            'name' => 'Loyal User',
            'email' => 'loyal@example.com',
        ]));

        $response = $this->get('/autentificare/google/callback');

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($existing);
        $this->assertSame(1, User::where('email', 'loyal@example.com')->count());
    }

    public function test_google_login_claims_guest_bookings_with_the_same_email(): void
    {
        $guestBooking = Booking::create([
            'booking_number' => 'SNC-GOOG1',
            'wing' => 'auto',
            'service_type' => 'revizie',
            'client_name' => 'Radu Google',
            'client_email' => 'radu.google@gmail.com',
            'client_phone' => '0711111111',
            'scheduled_at' => now()->addDays(2),
            'status' => 'pending',
        ]);

        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-uid-booking',
            'name' => 'Radu Google',
            'email' => 'radu.google@gmail.com',
        ]));

        $this->get('/autentificare/google/callback')->assertRedirect('/dashboard');

        $this->assertDatabaseHas('bookings', [
            'id' => $guestBooking->id,
            'user_id' => User::where('email', 'radu.google@gmail.com')->value('id'),
        ]);
    }

    // ─── Logica permisivă: email existent → logare directă ────────────────

    public function test_google_callback_logs_in_existing_local_account_with_same_email(): void
    {
        $local = User::factory()->create([
            'email' => 'ion.local@example.com',
            'password' => 'ParolaVeche123',
        ]);

        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-uid-dup',
            'name' => 'Ion Google',
            'email' => 'ion.local@example.com',
        ]));

        $response = $this->get('/autentificare/google/callback');

        // Logare directă pe contul local existent, fără verificarea parolei vechi.
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($local);

        $local->refresh();
        $this->assertSame(1, User::where('email', 'ion.local@example.com')->count(), 'Nu se creează cont duplicat.');
        $this->assertTrue(
            Hash::check('ParolaVeche123', $local->password),
            'Parola locală nu trebuie modificată de loginul Google.'
        );
    }

    // ─── Erori OAuth ──────────────────────────────────────────────────────

    public function test_google_callback_handles_denied_consent(): void
    {
        $response = $this->get('/autentificare/google/callback?error=access_denied');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_google_callback_with_invalid_state_shows_error_instead_of_server_error(): void
    {
        // Fără fake: Socialite real aruncă InvalidStateException (state absent
        // din sesiune) înainte de orice apel HTTP către Google.
        $response = $this->get('/autentificare/google/callback?state=falsified');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_google_callback_without_email_is_rejected(): void
    {
        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-uid-noemail',
            'name' => 'Fara Email',
            'email' => '',
        ]));

        $response = $this->get('/autentificare/google/callback');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    // ─── Protecția rutelor ────────────────────────────────────────────────

    public function test_authenticated_user_cannot_access_google_routes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/autentificare/google')
            ->assertRedirect(route('home'));
    }

    public function test_dashboard_alias_requires_authentication(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));

        $user = User::factory()->create();
        $this->actingAs($user)->get('/dashboard')->assertOk();
    }

    // ─── Interfața ────────────────────────────────────────────────────────

    public function test_login_and_register_pages_show_google_button(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Continuă cu Google')
            ->assertSee(route('google.redirect'), false);

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Continuă cu Google')
            ->assertSee(route('google.redirect'), false);
    }
}

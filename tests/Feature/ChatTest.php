<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The default admin password from config/app.php.
     */
    private const ADMIN_PASSWORD = 'sanctuary2026';

    // ─── Public chat (guests) ───────────────────────────────────────

    public function test_guest_can_send_a_message(): void
    {
        $response = $this->post('/api/chat/message', [
            'message' => 'Bună ziua! Aveți locuri disponibile?',
        ], ['Accept' => 'application/json']);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'message'     => 'Bună ziua! Aveți locuri disponibile?',
            'from_user'   => true,
            'sender_name' => 'Vizitator',
        ]);

        $this->assertDatabaseHas('chat_messages', [
            'message' => 'Bună ziua! Aveți locuri disponibile?',
            'user_id' => null,
            'from_user' => 1,
        ]);
    }

    public function test_guest_can_load_their_messages(): void
    {
        // Un browser real trimite cookie-ul de sesiune la fiecare cerere, deci
        // fixăm un session ID comun pentru a simula același vizitator.
        $this->withCookie(config('session.cookie'), str_repeat('a', 40));

        $this->post('/api/chat/message', ['message' => 'Mesaj de test'], ['Accept' => 'application/json'])
            ->assertStatus(201);

        $response = $this->get('/api/chat/messages', ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Mesaj de test']);
    }

    public function test_message_validation_rejects_empty_message(): void
    {
        // Widgetul trimite cu Accept: application/json, deci Laravel răspunde
        // cu 422 + erori JSON (nu redirect cu erori în sesiune).
        $response = $this->post('/api/chat/message', [
            'message' => '',
        ], ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
        $this->assertEquals(0, ChatMessage::count());
    }

    public function test_message_validation_rejects_oversized_message(): void
    {
        $response = $this->post('/api/chat/message', [
            'message' => str_repeat('a', 1001),
        ], ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
        $this->assertEquals(0, ChatMessage::count());
    }

    // ─── Admin login ────────────────────────────────────────────────

    public function test_admin_login_page_renders(): void
    {
        $response = $this->get('/admin/chat/login');

        $response->assertStatus(200);
        $response->assertSee('Parolă Admin');
    }

    public function test_admin_can_login_with_correct_password(): void
    {
        $response = $this->post('/admin/chat/login', [
            'password' => self::ADMIN_PASSWORD,
        ]);

        $response->assertRedirect(route('admin.chat.index'));
        $response->assertSessionHas('admin_logged_in');
    }

    public function test_admin_login_fails_with_wrong_password(): void
    {
        $response = $this->post('/admin/chat/login', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['password']);
        $response->assertSessionMissing('admin_logged_in');
    }

    // ─── Admin panel protection ─────────────────────────────────────

    public function test_guest_is_redirected_from_admin_panel(): void
    {
        $response = $this->get('/admin/chat');

        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_access_conversation_or_reply(): void
    {
        ChatMessage::create([
            'session_id'  => 'guestsession123',
            'message'     => 'Salut, am o întrebare.',
            'sender_name' => 'Vizitator',
            'from_user'   => true,
        ]);

        $this->get('/admin/chat/guestsession123')->assertRedirect(route('admin.login'));

        $this->post('/admin/chat/guestsession123/reply', ['message' => 'Răspuns'])
            ->assertRedirect(route('admin.login'));

        $this->assertEquals(1, ChatMessage::count());
    }

    // ─── Admin panel functionality ─────────────────────────────────

    public function test_admin_sees_conversations_list(): void
    {
        ChatMessage::create([
            'session_id'  => 'guestsession123',
            'message'     => 'Salut, am o întrebare despre garaj.',
            'sender_name' => 'Ion Popescu',
            'from_user'   => true,
        ]);

        $response = $this->withSession(['admin_logged_in' => true])
            ->get('/admin/chat');

        $response->assertStatus(200);
        $response->assertSee('Ion Popescu');
        $response->assertSee('Salut, am o întrebare despre garaj.');
    }

    public function test_admin_can_view_a_conversation(): void
    {
        ChatMessage::create([
            'session_id'  => 'guestsession123',
            'message'     => 'Bună, ce program aveți?',
            'sender_name' => 'Maria Ionescu',
            'from_user'   => true,
        ]);

        $response = $this->withSession(['admin_logged_in' => true])
            ->get('/admin/chat/guestsession123');

        $response->assertStatus(200);
        $response->assertSee('Maria Ionescu');
        // Pagina inițializează componenta JS care face polling pe conversație.
        $response->assertSee("adminChat('guestsession123')", false);

        // Mesajele nu sunt randate server-side; sunt livrate de endpoint-ul JSON
        // pe care pagina îl interoghează — verificăm că acesta conține mesajul.
        $this->withSession(['admin_logged_in' => true])
            ->getJson('/admin/chat/guestsession123/mesaje')
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Bună, ce program aveți?']);
    }

    public function test_admin_can_reply_to_a_conversation(): void
    {
        ChatMessage::create([
            'session_id'  => 'guestsession123',
            'message'     => 'Bună, ce program aveți?',
            'sender_name' => 'Maria Ionescu',
            'from_user'   => true,
        ]);

        $response = $this->withSession(['admin_logged_in' => true])
            ->postJson('/admin/chat/guestsession123/reply', [
                'message' => 'Bună! Suntem deschiși 08:00–22:00.',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('chat_messages', [
            'session_id'  => 'guestsession123',
            'message'     => 'Bună! Suntem deschiși 08:00–22:00.',
            'sender_name' => 'Sanctuary Support',
            'from_user'   => 0,
        ]);
    }

    public function test_admin_reply_is_returned_to_the_visitor(): void
    {
        ChatMessage::create([
            'session_id'  => 'guestsession123',
            'message'     => 'Salut!',
            'sender_name' => 'Vizitator',
            'from_user'   => true,
        ]);

        $this->withSession(['admin_logged_in' => true])
            ->postJson('/admin/chat/guestsession123/reply', [
                'message' => 'Salut! Cu ce te pot ajuta?',
            ]);

        $messages = ChatMessage::where('session_id', 'guestsession123')
            ->orderBy('created_at', 'asc')
            ->get();

        $this->assertCount(2, $messages);
        $this->assertEquals('Salut!', $messages[0]->message);
        $this->assertEquals('Salut! Cu ce te pot ajuta?', $messages[1]->message);
        $this->assertFalse((bool) $messages[1]->from_user);
    }

    public function test_admin_messages_endpoint_returns_json_for_polling(): void
    {
        ChatMessage::create([
            'session_id'  => 'guestsession123',
            'message'     => 'Mesaj pentru polling',
            'sender_name' => 'Vizitator',
            'from_user'   => true,
        ]);

        $response = $this->withSession(['admin_logged_in' => true])
            ->getJson('/admin/chat/guestsession123/mesaje');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message'     => 'Mesaj pentru polling',
            'sender_name' => 'Vizitator',
        ]);
    }

    public function test_admin_cannot_reply_to_nonexistent_conversation(): void
    {
        $response = $this->withSession(['admin_logged_in' => true])
            ->postJson('/admin/chat/does-not-exist/reply', [
                'message' => 'Răspuns pentru conversație inexistentă.',
            ]);

        $response->assertStatus(404);
        $this->assertEquals(0, ChatMessage::count());
    }

    public function test_admin_logout_clears_session(): void
    {
        $response = $this->withSession(['admin_logged_in' => true])
            ->post('/admin/chat/logout');

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionMissing('admin_logged_in');
    }

    public function test_admin_reply_validation_rejects_empty_message(): void
    {
        ChatMessage::create([
            'session_id'  => 'guestsession123',
            'message'     => 'Salut',
            'sender_name' => 'Vizitator',
            'from_user'   => true,
        ]);

        $response = $this->withSession(['admin_logged_in' => true])
            ->postJson('/admin/chat/guestsession123/reply', ['message' => '']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
        $this->assertEquals(1, ChatMessage::count());
    }

    // ─── Tawk.to-style: mesaje necitite, read receipts, typing ──────

    public function test_guest_sees_unread_count_and_marks_messages_read_when_widget_opens(): void
    {
        // Fixăm un session ID comun pentru a simula același vizitator.
        $this->withCookie(config('session.cookie'), str_repeat('b', 40));
        $this->post('/api/chat/message', ['message' => 'Am o întrebare'], ['Accept' => 'application/json'])
            ->assertStatus(201);

        $sessionId = ChatMessage::first()->session_id;

        ChatMessage::create([
            'session_id'  => $sessionId,
            'message'     => 'Bună! Cu ce te ajut?',
            'sender_name' => 'Sanctuary Support',
            'from_user'   => false,
        ]);

        // Widget închis → mesajul admin apare ca necitit.
        $this->get('/api/chat/messages', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonPath('unread', 1);

        // Widget deschis → se marchează citit (read-receipt ✓✓ pentru admin).
        $this->get('/api/chat/messages?open=1', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonPath('unread', 0);

        $this->assertNotNull(ChatMessage::where('from_user', false)->first()->read_at);

        // Mesajul propriu al vizitatorului rămâne necitit până îl vede adminul.
        $this->assertNull(ChatMessage::where('from_user', true)->first()->read_at);
    }

    public function test_admin_conversation_list_reports_unread_and_clears_when_viewed(): void
    {
        ChatMessage::create([
            'session_id'  => 'guestsession123',
            'message'     => 'Salut, am o întrebare.',
            'sender_name' => 'Ion Popescu',
            'from_user'   => true,
        ]);
        ChatMessage::create([
            'session_id'  => 'guestsession123',
            'message'     => 'Mai am una.',
            'sender_name' => 'Ion Popescu',
            'from_user'   => true,
        ]);
        ChatMessage::create([
            'session_id'  => 'guestsession123',
            'message'     => 'Salut!',
            'sender_name' => 'Sanctuary Support',
            'from_user'   => false,
        ]);

        $this->withSession(['admin_logged_in' => true])
            ->getJson('/admin/chat/conversations')
            ->assertStatus(200)
            ->assertJsonPath('total_unread', 2)
            ->assertJsonPath('conversations.0.unread', 2);

        // Adminul deschide conversația → badge-ul din listă se golește.
        $this->withSession(['admin_logged_in' => true])
            ->get('/admin/chat/guestsession123')
            ->assertStatus(200);

        $this->withSession(['admin_logged_in' => true])
            ->getJson('/admin/chat/conversations')
            ->assertStatus(200)
            ->assertJsonPath('total_unread', 0);
    }

    public function test_conversations_json_requires_admin_login(): void
    {
        $this->getJson('/admin/chat/conversations')
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_polling_marks_guest_messages_as_read(): void
    {
        ChatMessage::create([
            'session_id'  => 'guestsession123',
            'message'     => 'Vezi mesajul?',
            'sender_name' => 'Vizitator',
            'from_user'   => true,
        ]);
        $this->assertNull(ChatMessage::first()->read_at);

        $this->withSession(['admin_logged_in' => true])
            ->getJson('/admin/chat/guestsession123/mesaje')
            ->assertStatus(200);

        $this->assertNotNull(ChatMessage::first()->read_at);
    }

    public function test_guest_typing_indicator_visible_to_admin(): void
    {
        $this->withCookie(config('session.cookie'), str_repeat('d', 40));
        $this->post('/api/chat/message', ['message' => 'Alo'], ['Accept' => 'application/json'])
            ->assertStatus(201);

        $sessionId = ChatMessage::first()->session_id;

        // Vizitatorul scrie → adminul vede indicatorul.
        $this->get('/api/chat/messages?typing=1', ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->withSession(['admin_logged_in' => true])
            ->getJson('/admin/chat/' . $sessionId . '/mesaje')
            ->assertStatus(200)
            ->assertJsonPath('guest_typing', true);

        // S-a oprit din scris → dispare la următorul poll.
        $this->get('/api/chat/messages', ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->withSession(['admin_logged_in' => true])
            ->getJson('/admin/chat/' . $sessionId . '/mesaje')
            ->assertStatus(200)
            ->assertJsonPath('guest_typing', false);
    }

    public function test_admin_typing_indicator_visible_to_guest(): void
    {
        $this->withCookie(config('session.cookie'), str_repeat('e', 40));
        $this->post('/api/chat/message', ['message' => 'Salut'], ['Accept' => 'application/json'])
            ->assertStatus(201);

        $sessionId = ChatMessage::first()->session_id;

        // Adminul scrie → vizitatorul vede indicatorul.
        $this->withSession(['admin_logged_in' => true])
            ->getJson('/admin/chat/' . $sessionId . '/mesaje?typing=1')
            ->assertStatus(200);

        $this->get('/api/chat/messages', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonPath('admin_typing', true);

        // Adminul s-a oprit din scris → dispare la următorul poll al adminului.
        $this->withSession(['admin_logged_in' => true])
            ->getJson('/admin/chat/' . $sessionId . '/mesaje')
            ->assertStatus(200);

        $this->get('/api/chat/messages', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonPath('admin_typing', false);
    }

    // ─── Prezența adminului: Online / Offline în widget ─────────────

    public function test_widget_shows_offline_when_admin_is_not_logged_in(): void
    {
        $this->get('/api/chat/messages', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonPath('admin_online', false);
    }

    public function test_widget_shows_online_when_admin_session_is_active(): void
    {
        // Orice cerere autentificată a adminului (polling-ul panoului) marchează
        // prezența → widget-ul vizitatorului primește admin_online=true.
        $this->withSession(['admin_logged_in' => true])
            ->getJson('/admin/chat/conversations')
            ->assertStatus(200);

        $this->get('/api/chat/messages', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonPath('admin_online', true);
    }

    public function test_widget_goes_offline_immediately_after_admin_logout(): void
    {
        // Adminul e activ (prezența e setată de middleware).
        $this->withSession(['admin_logged_in' => true])
            ->getJson('/admin/chat/conversations')
            ->assertStatus(200);

        $this->get('/api/chat/messages', ['Accept' => 'application/json'])
            ->assertJsonPath('admin_online', true);

        // Deconectare → widget-ul află imediat (fără a aștepta TTL-ul de 15s).
        $this->withSession(['admin_logged_in' => true])
            ->post('/admin/chat/logout');

        $this->get('/api/chat/messages', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonPath('admin_online', false);
    }
}

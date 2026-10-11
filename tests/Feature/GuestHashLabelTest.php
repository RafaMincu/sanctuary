<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminChatController;
use App\Models\ChatMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

class GuestHashLabelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Când sender_name = 'Vizitator' (fără hash), metoda returnează null
     * ca să cadă pe fallback-ul guestLabel() cu hash unic.
     */
    public function test_guest_sender_name_vizitator_falls_back_to_hash_label(): void
    {
        $sessionId = 'sess_aabbcc';

        ChatMessage::create([
            'session_id'  => $sessionId,
            'message'     => 'Salut!',
            'sender_name' => 'Vizitator',
            'from_user'   => true,
        ]);

        $controller = app(AdminChatController::class);

        // Testăm senderNameForSession prin reflection.
        $nameMethod = new ReflectionMethod($controller, 'senderNameForSession');
        $nameMethod->setAccessible(true);
        $result = $nameMethod->invoke($controller, $sessionId);

        // Pentru „Vizitator" gol → null (fallback la guestLabel).
        $this->assertNull(
            $result,
            'senderNameForSession must return null for plain "Vizitator"'
        );

        // Verificăm guestLabel.
        $labelMethod = new ReflectionMethod($controller, 'guestLabel');
        $labelMethod->setAccessible(true);
        $label = $labelMethod->invoke($controller, $sessionId);

        $clean     = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $sessionId));
        $expected  = strlen($clean) >= 6 ? substr($clean, -6) : $clean;
        $expectedFull = "Vizitator {$expected}";

        $this->assertSame($expectedFull, $label);
        $this->assertEquals('Vizitator aabbcc', $label, 'Hash label must NOT contain #');
    }

    /**
     * Dacă mesajul are un nume real (client autenticat), nu se folosește hash.
     */
    public function test_named_sender_skips_hash_and_keeps_name(): void
    {
        $sessionId = 'sess_xyz123';

        ChatMessage::create([
            'session_id'  => $sessionId,
            'message'     => 'Bună!',
            'sender_name' => 'Ion Popescu',
            'from_user'   => true,
        ]);

        $controller = app(AdminChatController::class);
        $method = new ReflectionMethod($controller, 'senderNameForSession');
        $method->setAccessible(true);
        $result = $method->invoke($controller, $sessionId);

        $this->assertSame('Ion Popescu', $result);
    }
}

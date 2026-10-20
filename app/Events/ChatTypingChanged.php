<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Indicatorul „scrie..." — eveniment efemer, fără stocare.
 * $who: 'guest' (vizitatorul scrie) sau 'admin' (adminul scrie).
 */
class ChatTypingChanged implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $sessionKey,
        public bool $typing,
        public string $who,
        public ?string $name = null,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('visitor.chat.'.$this->sessionKey),
            new PrivateChannel('admin.chat'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'chat.typing';
    }

    public function broadcastWith(): array
    {
        return [
            'session_id' => $this->sessionKey,
            'typing'     => $this->typing,
            'who'        => $this->who,
            'name'       => $this->name,
        ];
    }
}

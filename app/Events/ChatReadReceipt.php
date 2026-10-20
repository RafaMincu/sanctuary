<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Confirmare de citire (✓✓) — actualizează interfața ambelor părți instant.
 * $reader: 'guest' (vizitatorul a citit mesajele adminului) sau 'admin'.
 */
class ChatReadReceipt implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $sessionKey,
        public string $reader,
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
        return 'chat.read';
    }

    public function broadcastWith(): array
    {
        return [
            'session_id' => $this->sessionKey,
            'reader'     => $this->reader,
        ];
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Prezența unui vizitator (Online / Offline) pentru panourile admin.
 */
class GuestPresenceChanged implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $sessionKey,
        public bool $online,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin.chat')];
    }

    public function broadcastAs(): string
    {
        return 'guest.presence';
    }

    public function broadcastWith(): array
    {
        return [
            'session_id'   => $this->sessionKey,
            'guest_online' => $this->online,
        ];
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Prezența adminului pentru toate widgeturile de chat (● Online / Offline).
 * Canal public — nu conține date sensibile, doar prezența și numele.
 */
class AdminPresenceChanged implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public bool $online,
        public ?string $adminName = null,
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('admin.presence')];
    }

    public function broadcastAs(): string
    {
        return 'admin.presence';
    }

    public function broadcastWith(): array
    {
        return [
            'admin_online' => $this->online,
            'admin_name'   => $this->adminName,
        ];
    }
}

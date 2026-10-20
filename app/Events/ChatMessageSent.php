<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Mesaj nou în live chat — trimis instant atât widgetului vizitatorului,
 * cât și panourilor admin (fără a mai aștepta polling-ul).
 */
class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public ChatMessage $message) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('visitor.chat.'.$this->message->session_id),
            new PrivateChannel('admin.chat'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'chat.message';
    }

    public function broadcastWith(): array
    {
        return [
            'id'          => $this->message->id,
            'session_id'  => $this->message->session_id,
            'message'     => $this->message->message,
            'from_user'   => (bool) $this->message->from_user,
            'sender_name' => $this->message->sender_name,
            'time'        => $this->message->created_at->format('H:i'),
            'created_at'  => $this->message->created_at->toISOString(),
            'read_at'     => $this->message->read_at?->toISOString(),
        ];
    }
}

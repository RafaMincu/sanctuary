<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    /**
     * Get the session identifier for the current visitor (auth user ID or session ID).
     */
    private function getSessionKey(Request $request): string
    {
        if (Auth::check()) {
            return 'user_' . Auth::id();
        }

        // Start session if not started, return session ID
        if (!$request->hasSession()) {
            $request->session()->start();
        }

        return $request->session()->getId();
    }

    /**
     * Return messages for the current visitor.
     *
     * Query params:
     *  - open=1   → widgetul e deschis: mesajele de la admin se marchează citite
     *               (alimentează badge-ul „mesaje necitite" + read-receipts ✓✓).
     *  - typing=1 → vizitatorul scrie: setează indicatorul „scrie..." pentru admin (5s).
     */
    public function index(Request $request)
    {
        $sessionKey = $this->getSessionKey($request);

        // Widgetul e deschis → mesajele de la admin au fost văzute.
        if ($request->query('open')) {
            ChatMessage::where('session_id', $sessionKey)
                ->where('from_user', false)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        // Prezența vizitatorului pentru panoul admin (oglinda lui chat:admin_online):
        // reînnoită la fiecare poll al widget-ului. Dacă vizitatorul închide
        // browserul, expiră singură prin TTL → „Offline” în panou.
        Cache::put('chat:guest_online:' . $sessionKey, true, now()->addSeconds(15));

        // Vizitatorul scrie → indicator pentru panoul admin (dispare imediat
        // la următorul poll fără typing, altfel stă max. 5s prin TTL).
        if ($request->query('typing')) {
            Cache::put('chat:typing:guest:' . $sessionKey, true, now()->addSeconds(5));
        } else {
            Cache::forget('chat:typing:guest:' . $sessionKey);
        }

        $messages = ChatMessage::where('session_id', $sessionKey)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($m) => [
                'id'          => $m->id,
                'message'     => $m->message,
                'from_user'   => (bool) $m->from_user,
                'sender_name' => $m->sender_name,
                'time'        => $m->created_at->format('H:i'),
                'created_at'  => $m->created_at->toISOString(),
                'read_at'     => $m->read_at?->toISOString(),
            ]);

        // Mesaje de la admin încă necitite de vizitator.
        $unread = ChatMessage::where('session_id', $sessionKey)
            ->where('from_user', false)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'messages'     => $messages,
            'unread'       => $unread,
            'admin_typing' => (bool) Cache::get('chat:typing:admin:' . $sessionKey, false),
            // Prezența vizitatorului → header-ul panoului admin: „Online" / „Offline".
            'guest_online' => true,
            // Prezența adminului → header-ul widget-ului: „● Online” / „● Offline”.
            'admin_online' => (bool) Cache::get('chat:admin_online', false),
            // Numele adminului activ → „Ana scrie...” / „Online • Ana”.
            'admin_name'   => Cache::get('chat:admin_name'),
        ]);
    }

    /**
     * Store a new message from the current visitor.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message'     => ['required', 'string', 'max:1000'],
            'sender_name' => ['sometimes', 'string', 'max:80'],
        ]);

        $sessionKey  = $this->getSessionKey($request);
        $senderName  = Auth::check()
            ? (Auth::user()->name ?? 'User')
            : ($validated['sender_name'] ?? 'Vizitator');

        // Mesaj trimis → indicatorul „scrie..." dispare imediat.
        Cache::forget('chat:typing:guest:' . $sessionKey);

        $msg = ChatMessage::create([
            'user_id'     => Auth::id(),          // null for guests
            'session_id'  => $sessionKey,
            'message'     => $validated['message'],
            'sender_name' => $senderName,
            'from_user'   => true,
        ]);

        return response()->json([
            'id'          => $msg->id,
            'message'     => $msg->message,
            'from_user'   => true,
            'sender_name' => $msg->sender_name,
            'time'        => $msg->created_at->format('H:i'),
            'created_at'  => $msg->created_at->toISOString(),
            'read_at'     => null,   // devine ✓✓ când adminul deschide conversația
        ], 201);
    }
}

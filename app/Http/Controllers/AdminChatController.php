<?php

namespace App\Http\Controllers;

use App\Events\AdminPresenceChanged;
use App\Events\ChatMessageSent;
use App\Events\ChatReadReceipt;
use App\Events\ChatTypingChanged;
use App\Models\Admin;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminChatController extends Controller
{
    /**
     * Show admin login form.
     */
    public function loginForm()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.chat.index');
        }
        return view('admin.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [], [
            'email' => 'email',
        ]);

        $admin = Admin::where('email', strtolower($request->email))->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['password' => 'Email sau parolă incorectă.']);
        }

        $request->session()->put('admin_logged_in', true);
        $request->session()->put('admin_id', $admin->id);
        $request->session()->put('admin_name', $admin->name);

        // Prezența + numele adminului pentru widget-ul vizitatorului (instant).
        Cache::put('chat:admin_online', true, now()->addSeconds(15));
        Cache::put('chat:admin_name', $admin->name, now()->addSeconds(15));

        // Broadcast instant: ● Online în toate widgeturile conectate.
        AdminPresenceChanged::dispatch(true, $admin->name);

        return redirect()->route('admin.chat.index');
    }

    /**
     * Admin logout.
     */
    public function logout(Request $request)
    {
        $request->session()->forget(['admin_logged_in', 'admin_id', 'admin_name']);

        // Deconectare → widget-ul vizitatorului afișează „Offline” imediat
        // (fără a aștepta expirarea TTL-ului din middleware).
        Cache::forget('chat:admin_online');
        Cache::forget('chat:admin_name');

        // Broadcast instant: ● Offline în toate widgeturile conectate.
        AdminPresenceChanged::dispatch(false);

        return redirect()->route('admin.login');
    }

    /**
     * Gruparea conversațiilor (shared între lista HTML și endpoint-ul JSON de polling).
     */
    private function conversationRows()
    {
        return ChatMessage::select(
                'session_id',
                DB::raw('MAX(created_at) as last_at'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('session_id')
            ->orderByDesc('last_at')
            ->get()
            ->map(function ($row) {
                // Numele clientului: luăm ultimul mesaj trimis de el, nu un
                // răspuns al adminului din aceeași conversație.
                $senderName = $this->senderNameForSession($row->session_id);

                // Mesaje necitite REALE: trimise de vizitator și încă nevăzute de admin.
                $unread = ChatMessage::where('session_id', $row->session_id)
                    ->where('from_user', true)
                    ->whereNull('read_at')
                    ->count();

                $lastMsg = ChatMessage::where('session_id', $row->session_id)
                    ->orderByDesc('created_at')
                    ->value('message');

                $displayName = $senderName ?: $this->guestLabel($row->session_id);

                return [
                    'session_id'   => $row->session_id,
                    'sender_name'  => $displayName,
                    'sender_initial' => mb_strtoupper(mb_substr($displayName, 0, 1)),
                    'guest_online' => (bool) Cache::get('chat:guest_online:' . $row->session_id, false),
                    'last_at'      => $row->last_at,
                    'total'        => $row->total,
                    'unread'       => $unread,
                    'last_message' => $lastMsg,
                ];
            });
    }

    /**
     * List all conversations grouped by session_id, sorted by latest message.
     */
    public function index()
    {
        $conversations = $this->conversationRows();
        $totalUnread = (int) $conversations->sum('unread');

        return view('admin.chat.index', compact('conversations', 'totalUnread'));
    }

    /**
     * Conversațiile ca JSON — folosit de polling-ul din lista admin
     * (actualizare badge-uri în timp real, fără reîncărcarea paginii).
     */
    public function conversations()
    {
        $conversations = $this->conversationRows();

        return response()->json([
            'conversations' => $conversations->values(),
            'total_unread'  => (int) $conversations->sum('unread'),
        ]);
    }

    /**
     * Show a single conversation.
     */
    public function show(string $sessionId)
    {
        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($messages->isEmpty()) {
            abort(404);
        }

        // Adminul a deschis conversația → mesajele vizitatorului devin citite
        // (badge-ul din listă se golește).
        ChatMessage::where('session_id', $sessionId)
            ->where('from_user', true)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $senderName = $this->senderNameForSession($sessionId) ?: $this->guestLabel($sessionId);

        // Prezența vizitatorului la prima încărcare (apoi se actualizează live la poll).
        $guestOnline = (bool) Cache::get('chat:guest_online:' . $sessionId, false);

        return view('admin.chat.show', compact('messages', 'sessionId', 'senderName', 'guestOnline'));
    }

    /**
     * Returnează numele ultimului mesaj trimis de client pentru conversație.
     */
    private function senderNameForSession(string $sessionId): ?string
    {
        $name = ChatMessage::where('session_id', $sessionId)
            ->where('from_user', true)
            ->whereNotNull('sender_name')
            ->where('sender_name', '!=', '')
            ->orderByDesc('created_at')
            ->value('sender_name');

        // Placeholder-ul generic „Vizitator" (fără hash) indică faptul că
        // trebuie folosită eticheta unică generată din sesiune.
        if ($name === 'Vizitator') {
            return null;
        }

        return $name;
    }

    private function guestLabel(string $sessionId): string
    {
        $clean = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $sessionId));
        // Ultimii 6 caractere alfanumerici ai hash-ului de sesiune — suficient de
        // diferiți între două sesiuni pentru a le diferenția rapid.
        $hash  = strlen($clean) >= 6 ? substr($clean, -6) : $clean;
        return "Vizitator {$hash}";
    }

    /**
     * Return messages of a conversation as JSON (used by the admin panel polling).
     *
     * Query params:
     *  - typing=1 → adminul scrie: setează indicatorul „scrie..." pentru vizitator (5s).
     * Mesajele vizitatorului se marchează citite la fiecare fetch (adminul le vede).
     */
    public function messages(Request $request, string $sessionId)
    {
        if ($request->query('typing')) {
            $wasTyping = Cache::get('chat:typing:admin:'.$sessionId);
            Cache::put('chat:typing:admin:' . $sessionId, true, now()->addSeconds(5));

            // Adminul a început să scrie → eveniment instant pentru vizitator.
            if (! $wasTyping) {
                ChatTypingChanged::dispatch(
                    $sessionId,
                    true,
                    'admin',
                    $request->session()->get('admin_name')
                );
            }
        } else {
            // Adminul s-a oprit din scris → indicatorul dispare imediat.
            $wasTyping = Cache::pull('chat:typing:admin:'.$sessionId);

            if ($wasTyping) {
                ChatTypingChanged::dispatch($sessionId, false, 'admin');
            }
        }

        $markedRead = ChatMessage::where('session_id', $sessionId)
            ->where('from_user', true)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Confirmare de citire (✓✓) → instant pentru vizitator, nu la poll.
        if ($markedRead > 0) {
            ChatReadReceipt::dispatch($sessionId, 'admin');
        }

        $messages = ChatMessage::where('session_id', $sessionId)
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

        return response()->json([
            'messages'    => $messages,
            'guest_typing' => (bool) Cache::get('chat:typing:guest:' . $sessionId, false),
            // Prezența vizitatorului → header-ul panoului: „Online" / „Offline"
            // (oglinda lui chat:admin_online din widget).
            'guest_online' => (bool) Cache::get('chat:guest_online:' . $sessionId, false),
        ]);
    }

    /**
     * Store a reply from the admin.
     */
    public function reply(Request $request, string $sessionId)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        // Ensure the session exists
        if (!ChatMessage::where('session_id', $sessionId)->exists()) {
            abort(404);
        }

        // Răspuns trimis → indicatorul „scrie..." dispare imediat.
        Cache::forget('chat:typing:admin:' . $sessionId);

        $msg = ChatMessage::create([
            'session_id'  => $sessionId,
            'message'     => $request->message,
            'from_user'   => false,   // false = from admin/support
            // Fiecare admin semnează cu numele lui.
            'sender_name' => $request->session()->get('admin_name', 'Sanctuary Support'),
        ]);

        // Răspuns nou → broadcast instant în widgetul vizitatorului (WS).
        ChatMessageSent::dispatch($msg);
        ChatTypingChanged::dispatch($sessionId, false, 'admin');

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Mesaj trimis!');
    }
}

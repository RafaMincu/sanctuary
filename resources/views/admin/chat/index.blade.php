@extends('layouts.sanctuary')

@section('title', 'Conversații Live Chat | The Sanctuary')

@section('content')
    <section class="relative z-10 max-w-5xl mx-auto px-6 pt-10 pb-20">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <p class="text-zinc-500 text-xs uppercase tracking-[0.25em] font-mono mb-1">// Panou Control // Live Chat</p>
                <h1 class="text-2xl sm:text-4xl font-black uppercase text-white tracking-wide">
                    Conversații <span class="bg-gradient-to-r from-cyan-400 to-emerald-400 bg-clip-text text-transparent">Live</span>
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <span class="px-3 py-2 text-[10px] font-mono uppercase tracking-wider text-emerald-400 border border-emerald-500/30 bg-emerald-950/20 rounded-sm">
                    ● Online
                </span>
                <span id="chat-total-unread"
                      class="px-3 py-2 text-[10px] font-mono uppercase tracking-wider text-white bg-red-500/90 rounded-sm"
                      style="{{ ($totalUnread ?? 0) > 0 ? '' : 'display:none;' }}">
                    <span id="chat-total-unread-count">{{ $totalUnread ?? 0 }}</span> necitite
                </span>
                <form action="{{ route('admin.chat.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 border border-zinc-800 text-zinc-300 text-xs font-bold uppercase tracking-wider hover:border-red-500/50 hover:text-red-300 transition-colors rounded-sm">
                        Deconectare
                    </button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-sm border border-emerald-500/40 bg-emerald-950/20 text-emerald-300 text-xs backdrop-blur-md flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Listă conversații (pasă la rând, fără reîncărcare) -->
        <div class="bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm backdrop-blur-xl overflow-hidden">
            <div class="hidden sm:grid items-center gap-4 px-5 py-3 border-b border-zinc-900 text-[10px] font-mono uppercase tracking-wider text-zinc-500" style="grid-template-columns:210px minmax(0,1fr) 100px 140px;">
                <div>Identificare</div>
                <div>Ultimul Mesaj</div>
                <div>Mesaje</div>
                <div class="text-right">Ultima Activitate</div>
            </div>

            <div id="conversation-list">
                @if ($conversations->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-gradient-to-br from-cyan-500/20 to-emerald-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <p class="text-zinc-400 text-sm">Nicio conversație încă.</p>
                        <p class="text-zinc-600 text-xs font-mono mt-1">Mesajele vizitatorilor vor apărea aici în timp real.</p>
                    </div>
                @else
                    @foreach ($conversations as $conv)
                        <a href="{{ route('admin.chat.show', $conv['session_id']) }}"
                           data-session="{{ $conv['session_id'] }}"
                           class="grid grid-cols-1 sm:grid-cols-[210px_minmax(0,1fr)_100px_140px] items-center gap-x-4 gap-y-3 px-5 py-4 border-b border-zinc-900/60 last:border-0 hover:bg-cyan-500/5 transition-colors group overflow-hidden">
                            <div class="flex items-center gap-3 min-w-0 overflow-hidden">
                                {{-- Avatar cu inițiala + dot prezență --}}
                                <div class="relative w-8 h-8 shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-emerald-400 flex items-center justify-center text-black font-bold text-xs">
                                        {{ strtoupper(substr($conv['sender_name'], 0, 1)) }}
                                    </div>
                                    @if ($conv['guest_online'] ?? false)
                                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-[#0b0b0e]"></span>
                                    @else
                                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-zinc-600 border-2 border-[#0b0b0e]"></span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex min-w-0 items-center gap-2">
                                        <span class="text-white text-sm font-semibold group-hover:text-cyan-300 transition-colors truncate">{{ $conv['sender_name'] }}</span>
                                        <span data-unread-badge
                                              class="shrink-0 min-w-[20px] h-5 px-1.5 inline-flex items-center justify-center text-[10px] font-bold text-white bg-red-500 rounded-full"
                                              style="{{ $conv['unread'] > 0 ? '' : 'display:none;' }}">{{ $conv['unread'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span data-online-dot class="w-1.5 h-1.5 rounded-full {{ ($conv['guest_online'] ?? false) ? 'bg-emerald-400' : 'bg-zinc-600' }}"></span>
                                        <span data-online-text class="text-[11px] font-mono {{ ($conv['guest_online'] ?? false) ? 'text-emerald-400' : 'text-zinc-600' }}">{{ ($conv['guest_online'] ?? false) ? 'Online' : 'Offline' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="min-w-0 max-w-full overflow-hidden text-zinc-400 text-xs truncate whitespace-nowrap">
                                <span data-last-message>{{ $conv['last_message'] }}</span>
                            </div>
                            <div class="justify-self-start sm:justify-self-center">
                                <span class="inline-block px-2 py-0.5 text-[10px] font-mono rounded-sm bg-zinc-900 text-zinc-300 border border-zinc-800">
                                    {{ $conv['total'] }} mesaj{{ $conv['total'] > 1 ? 'e' : '' }}
                                </span>
                            </div>
                            <div class="text-left sm:text-right text-zinc-500 text-[11px] font-mono whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($conv['last_at'])->diffForHumans() }}
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        </div>

        <!-- Buleta de comutare rapidă între conversații -->
        @include('admin.chat._switcher', ['currentSession' => null])
    </section>

    <script>
    (function () {
        const routeShowPrefix = @json(route('admin.chat.show', ['sessionId' => '__SID__'])).replace('__SID__', '');

        // ── Actualizare automată la fiecare 3 secunde ──────────────────
        
        let prevHash = '';
        
        function poll() {
            fetch('/admin/chat/conversations')
                .then(res => res.json())
                .then(data => {
                    const convs = data.conversations;
                    const total = data.total_unread;
                    
                    // Hash comparare pentru a evita redraw-uri inutile
                    const currentHash = JSON.stringify(convs.map(c => ({
                        id: c.session_id, 
                        online: c.guest_online, 
                        unread: c.unread,
                        msgLen: (c.last_message || '').length
                    })));
                    
                    if (currentHash === prevHash) return; // Nimici schimbat → skip
                    prevHash = currentHash;
                    
                    const listEl = document.getElementById('conversation-list');
                    
                    // Colectam sesiunile existente in DOM
                    const existingSessions = new Set();
                    listEl.querySelectorAll('[data-session]').forEach(el => {
                        existingSessions.add(el.getAttribute('data-session'));
                    });
                    
                    // Adaugam conversatiile NOUI (care nu sunt inca in pagină)
                    let newHtml = '';
                    convs.forEach(c => {
                        if (!existingSessions.has(c.session_id)) {
                            newHtml += buildRowHTML(c);
                        }
                    });
                    
                    // Inseram prima in lista
                    if (newHtml && listEl.children.length > 0) {
                        listEl.insertAdjacentHTML('afterbegin', newHtml);
                        const emptyMsg = listEl.querySelector('.p-12.text-center');
                        if (emptyMsg) emptyMsg.remove();
                    } else if (newHtml && listEl.children.length === 0) {
                        listEl.innerHTML = newHtml;
                    }
                    
                    // Actualizam TOATE conversatiile (nu doar cele noi)
                    convs.forEach(c => {
                        const el = listEl.querySelector('[data-session="' + c.session_id + '"]');
                        if (!el) return;
                        
                        // Status ONLINE/OFFLINE → dot verde/gri
                        const dot = el.querySelector('[data-status-dot="' + c.session_id + '"]');
                        if (dot) {
                            dot.className = 'absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full border-2 border-[#0b0b0e] status-dot-' + c.session_id + ' ' + (c.guest_online ? 'bg-emerald-400' : 'bg-zinc-600');
                        }
                        
                        // Text Online/Offline
                        const txt = el.querySelector('[data-status-text="' + c.session_id + '"]');
                        if (txt) {
                            txt.textContent = c.guest_online ? 'Online' : 'Offline';
                            txt.className = 'text-[11px] font-mono status-text-' + c.session_id + ' ' + (c.guest_online ? 'text-emerald-400' : 'text-zinc-600');
                        }
                        
                        // Badge necitite
                        const badge = el.querySelector('[data-unread-badge="' + c.session_id + '"]');
                        if (badge) {
                            badge.textContent = c.unread;
                            badge.style.display = c.unread > 0 ? '' : 'none';
                        }
                        
                        // Ultimul mesaj
                        const last = el.querySelector('[data-last-message="' + c.session_id + '"]');
                        if (last && c.last_message) last.textContent = c.last_message;
                    });
                    
                    // Actualizam totalul de necitite din header
                    const globalBadge = document.getElementById('chat-total-unread');
                    const globalCount = document.getElementById('chat-total-unread-count');
                    
                    if (globalBadge && globalCount) {
                        globalCount.textContent = total;
                        globalBadge.style.display = total > 0 ? '' : 'none';
                    }
                })
                .catch(() => {});
        }
        
        // Generam HTML pentru o singura rand de conversatie
        function buildRowHTML(row) {
            const sid = row.session_id;
            return '<a href="' + routeShowPrefix.replace('__SID__', sid) + '" data-session="' + sid + '" class="grid grid-cols-1 sm:grid-cols-[210px_minmax(0,1fr)_100px_140px] items-center gap-x-4 gap-y-3 px-5 py-4 border-b border-zinc-900/60 last:border-0 hover:bg-cyan-500/5 transition-colors group overflow-hidden">' +
                '<div class="flex items-center gap-3 min-w-0 overflow-hidden">' +
                    '<div class="relative w-8 h-8 shrink-0">' +
                        '<div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-emerald-400 flex items-center justify-center text-black font-bold text-sm">' + (row.sender_initial || 'V') + '</div>' +
                        '<span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full border-2 border-[#0b0b0e] status-dot-' + sid + ' ' + (row.guest_online ? 'bg-emerald-400' : 'bg-zinc-600') + '" data-status-dot="' + sid + '"></span>' +
                    '</div>' +
                    '<div class="min-w-0 flex-1">' +
                        '<div class="flex min-w-0 items-center gap-2">' +
                            '<span class="text-white text-sm font-semibold truncate">' + row.sender_name + '</span>' +
                            '<span class="unread-badge-' + sid + '" data-unread-badge="' + sid + '" style="' + (row.unread > 0 ? '' : 'display:none;') + '">' + row.unread + '</span>' +
                        '</div>' +
                        '<div class="flex items-center gap-1.5 mt-0.5">' +
                            '<span class="status-text-' + sid + ' text-[11px] font-mono ' + (row.guest_online ? 'text-emerald-400' : 'text-zinc-600') + '" data-status-text="' + sid + '">' + (row.guest_online ? 'Online' : 'Offline') + '</span>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="min-w-0 max-w-full overflow-hidden text-zinc-400 text-xs"><span data-last-message="' + sid + '">' + (row.last_message || '—') + '</span></div>' +
                '<div class="justify-self-start sm:justify-self-center"><span class="inline-block px-2 py-0.5 text-[10px] font-mono rounded-sm bg-zinc-900 text-zinc-300 border border-zinc-800">' + row.total + ' mesaje</span></div>' +
                '<div class="text-left sm:text-right text-zinc-500 text-[11px] font-mono">—</div>' +
            '</a>';
        }
        
// Initializam polling-ul (fallback la 10s) + real-time via WebSocket.

        poll();

        // Real-time (Reverb):
        // mesajele noi și prezența vizitatorilor declanșează poll-ul instant,
        // fără a mai aștepta intervalul de 10s.
        if (window.Echo) {
            try {
                Echo.private('admin.chat')
                    .listen('.chat.message', sub { poll(); });
                    .listen('.guest.presence', sub { poll(); });
            } catch (e) {
                /* WS indisponibil → polling rămâne activ */
            }
        }

        setInterval(poll, 10000);
    })();
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    [data-session] {
        transition: background-color 0.2s ease;
    }
    [data-session]:hover {
        background-color: rgba(34, 211, 238, 0.05);
    }
</style>
@endsection

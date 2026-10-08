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
            <div class="hidden sm:grid grid-cols-12 gap-4 px-5 py-3 border-b border-zinc-900 text-[10px] font-mono uppercase tracking-wider text-zinc-500">
                <div class="col-span-3">Vizitator</div>
                <div class="col-span-5">Ultimul Mesaj</div>
                <div class="col-span-2">Mesaje</div>
                <div class="col-span-2 text-right">Ultima Activitate</div>
            </div>

            <div id="conversation-list">
                @if ($conversations->isEmpty())
                    <div class="bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm backdrop-blur-xl p-12 text-center">
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
                           class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 px-5 py-4 border-b border-zinc-900/60 last:border-0 hover:bg-cyan-500/5 transition-colors group">
                            <div class="sm:col-span-3 flex items-center gap-3">
                                <div class="w-8 h-8 shrink-0 rounded-full bg-gradient-to-br from-cyan-400 to-emerald-400 flex items-center justify-center text-black font-bold text-xs">
                                    {{ strtoupper(substr($conv['sender_name'], 0, 1)) }}
                                </div>
                                <span class="text-white text-sm font-semibold group-hover:text-cyan-300 transition-colors truncate">{{ $conv['sender_name'] }}</span>
                                <span data-unread-badge
                                      class="shrink-0 min-w-[20px] h-5 px-1.5 inline-flex items-center justify-center text-[10px] font-bold text-white bg-red-500 rounded-full"
                                      style="{{ $conv['unread'] > 0 ? '' : 'display:none;' }}">{{ $conv['unread'] }}</span>
                            </div>
                            <div class="sm:col-span-5 text-zinc-400 text-xs truncate self-center">
                                <span data-last-message>{{ $conv['last_message'] }}</span>
                            </div>
                            <div class="sm:col-span-2 self-center">
                                <span class="inline-block px-2 py-0.5 text-[10px] font-mono rounded-sm bg-zinc-900 text-zinc-300 border border-zinc-800">
                                    {{ $conv['total'] }} mesaj{{ $conv['total'] > 1 ? 'e' : '' }}
                                </span>
                            </div>
                            <div class="sm:col-span-2 sm:text-right text-zinc-500 text-[11px] font-mono self-center">
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
            const list = document.getElementById('conversation-list');
            const empty = list.querySelector('.bg-[#0b0b0e]/80');
            const hasEmpty = !!empty;

            // Buffer pentru a nu duplica rândul chat-ului nou (reîncărcare = F5)
            let reloadedForNew = false;

            function updateTotal(total) {
                const badge = document.getElementById('chat-total-unread');
                const count = document.getElementById('chat-total-unread-count');
                if (!badge || !count) return;
                count.textContent = total;
                badge.style.display = total > 0 ? '' : 'none';
            }

            function showEmptyState(html) {
                const container = list;
                if (empty) {
                    empty.outerHTML = html;
                } else {
                    container.innerHTML = html + container.innerHTML;
                }
            }

            function appendRow(html) {
                const container = list;
                if (hasEmpty) {
                    empty.outerHTML = html;
                    hasEmpty = false;
                } else {
                    container.insertAdjacentHTML('beforeend', html);
                }
            }

            function poll() {
                fetch(url, { headers: { 'Accept': 'application/json' } })
                    .then(r => r.ok ? r.json() : null)
                    .then(data => {
                        if (!data) return;
                        const convs = data.conversations || [];
                        const total = data.total_unread || 0;

                        // Chat nou → rând dinamic (nu reîncarcăm pagina)
                        const hasNew = convs.some(c => !known.includes(c.session_id));
                        if (hasNew && !reloadedForNew) {
                            reloadedForNew = true;
                            const row = convs.find(c => !known.includes(c.session_id));
                            if (row) {
                                appendRow(
                                    '<a href="' + /*route('admin.chat.show', $conv['session_id'])*/ + '" data-session="' + row.session_id + '" class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 px-5 py-4 border-b border-zinc-900/60 last:border-0 hover:bg-cyan-500/5 transition-colors group">' +
                                    '<div class="sm:col-span-3 flex items-center gap-3">' +
                                    '<div class="w-8 h-8 shrink-0 rounded-full bg-gradient-to-br from-cyan-400 to-emerald-400 flex items-center justify-center text-black font-bold text-xs">' + /*strtoupper(substr($conv['sender_name'], 0, 1))*/ + '</div>' +
                                    '<span class="text-white text-sm font-semibold group-hover:text-cyan-300 transition-colors truncate">' + row.sender_name + '</span>' +
                                    '<span data-unread-badge class="shrink-0 min-w-[20px] h-5 px-1.5 inline-flex items-center justify-center text-[10px] font-bold text-white bg-red-500 rounded-full" style="' + (row.unread > 0 ? '' : 'display:none;') + '">' + row.unread + '</span>' +
                                    '</div>' +
                                    '<div class="sm:col-span-5 text-zinc-400 text-xs truncate self-center"><span data-last-message>' + (row.last_message || '—') + '</span></div>' +
                                    '<div class="sm:col-span-2 self-center"><span class="inline-block px-2 py-0.5 text-[10px] font-mono rounded-sm bg-zinc-900 text-zinc-300 border border-zinc-800">' + row.total + ' mesaje</span></div>' +
                                    '<div class="sm:col-span-2 sm:text-right text-zinc-500 text-[11px] font-mono self-center">—</div>' +
                                    '</a>'
                                );
                            }
                            return;
                        }
                        reloadedForNew = false;

                        convs.forEach(c => {
                            const row = document.querySelector('[data-session="' + c.session_id + '"]');
                            if (!row) return;

                            const badge = row.querySelector('[data-unread-badge]');
                            if (badge) {
                                badge.textContent = c.unread;
                                badge.style.display = c.unread > 0 ? '' : 'none';
                            }

                            const last = row.querySelector('[data-last-message]');
                            if (last && c.last_message) last.textContent = c.last_message;
                        });

                        updateTotal(total);
                    })
                    .catch(() => {});
            }

            setInterval(poll, 5000);
        })();
    </script>
@endsection
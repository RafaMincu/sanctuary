{{--
    Buleta de comutare rapidă între conversațiile deschise (panou admin).
    Fixă jos-stânga, prezentă pe AMBELE pagini (listă + conversație).
    - click pe bilă → panou cu toate chat-urile deschise (live la 4s)
    - click pe un chat → navigare directă (comutare fără a te întoarce la listă)
    - badge cu totalul mesajelor necitite
    - notificări browser (la activare) + sunet la mesaje în alte conversații
--}}
@php
    // Conversația deschisă acum (null pe pagina de listă).
    $currentSession = $currentSession ?? null;
@endphp

<div x-data="chatSwitcher({{ e(json_encode($currentSession)) }})"
     x-init="init()"
     class="fixed bottom-6 left-6 z-40 flex flex-col items-start gap-3">

    {{-- Panou: lista chat-urilor deschise --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0 translate-y-2"
         @click.outside="open = false"
         class="w-80 max-h-96 flex flex-col bg-[#0b0b0e]/95 border border-zinc-800 rounded-sm backdrop-blur-xl shadow-2xl overflow-hidden"
         style="display: none;">

        <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-900"
             style="background: linear-gradient(135deg, rgba(34,211,238,0.08), rgba(52,211,153,0.05));">
            <span class="text-[10px] font-mono uppercase tracking-widest text-zinc-500">// Chat-uri deschise</span>
            <span class="text-[10px] font-mono text-cyan-400" x-text="convs.length + ' conversații'"></span>
        </div>

        <div class="overflow-y-auto flex-1" style="scrollbar-width: thin;">
            <template x-for="c in convs" :key="c.session_id">
                <a :href="showUrl(c.session_id)"
                   @click="open = false"
                   :class="c.session_id === current
                       ? 'border-l-2 border-cyan-400 bg-cyan-500/[0.06]'
                       : 'border-l-2 border-transparent'"
                   class="flex items-center gap-3 px-4 py-3 border-b border-zinc-900/60 last:border-0 hover:bg-zinc-900/40 transition-colors">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-gradient-to-br from-cyan-400 to-emerald-400 flex items-center justify-center text-black font-bold text-xs"
                         x-text="(c.sender_name || 'V').substring(0, 1).toUpperCase()"></div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-white text-xs font-semibold truncate" x-text="c.sender_name"></span>
                            <span class="text-zinc-600 text-[10px] font-mono shrink-0" x-text="shortTime(c.last_at)"></span>
                        </div>
                        <p class="text-zinc-500 text-[11px] truncate mt-0.5" x-text="c.last_message || '—'"></p>
                    </div>
                    <span x-show="c.unread > 0"
                          x-text="c.unread"
                          class="shrink-0 min-w-[20px] h-5 px-1.5 inline-flex items-center justify-center text-[10px] font-bold text-white bg-red-500 rounded-full"></span>
                </a>
            </template>

            <p x-show="convs.length === 0"
               class="px-4 py-6 text-center text-zinc-600 text-xs font-mono">
                Nicio conversație deschisă.
            </p>
        </div>

        <div class="flex items-center justify-between gap-2 px-4 py-2.5 border-t border-zinc-900 bg-[#060608]/60">
            <button x-show="notifPerm !== 'granted' && supportsNotif"
                    @click="requestNotif()"
                    class="text-[10px] font-mono uppercase tracking-wider text-amber-400 hover:text-amber-300 transition-colors">
                🔔 Activează notificări
            </button>
            <span x-show="supportsNotif && notifPerm === 'granted'"
                  class="text-[10px] font-mono uppercase tracking-wider text-emerald-500">
                🔔 Notificări active
            </span>
            <a href="{{ route('admin.chat.index') }}"
               class="text-[10px] font-mono uppercase tracking-wider text-zinc-500 hover:text-cyan-400 transition-colors ml-auto">
                Toate conversațiile →
            </a>
        </div>
    </div>

    {{-- Bila --}}
    <button @click="open = !open"
            :title="total > 0 ? total + ' mesaje necitite' : 'Comută între conversații'"
            class="relative w-14 h-14 rounded-full bg-gradient-to-br from-cyan-400 to-emerald-400 flex items-center justify-center text-black shadow-[0_0_20px_rgba(34,211,238,0.35)] hover:scale-105 active:scale-95 transition-transform">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <span x-show="total > 0"
              x-text="total > 99 ? '99+' : total"
              class="absolute -top-1 -right-1 min-w-[22px] h-[22px] px-1 inline-flex items-center justify-center text-[10px] font-black text-white bg-red-500 rounded-full border-2 border-[#0a0a0f]"></span>
        <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-[#0a0a0f] status-live"></span>
    </button>
</div>


<script>
    function chatSwitcher(current) {
        return {
            open: false,
            current: current,
            convs: [],
            total: 0,
            booted: false,
            prevUnread: {},           // session_id → necitite la poll-ul anterior
            notifPerm: (typeof Notification !== 'undefined') ? Notification.permission : 'denied',
            supportsNotif: (typeof Notification !== 'undefined'),

            showUrl(id) {
                return {{ json_encode(url('admin/chat')) }} + '/' + encodeURIComponent(id);
            },

            // „14:32” din timestamp-ul serverului (fără parsing de fus orar).
            shortTime(at) {
                if (!at || at.length < 16) return '';
                return at.substring(11, 16);
            },

            requestNotif() {
                if (!this.supportsNotif) return;
                Notification.requestPermission().then(p => { this.notifPerm = p; });
            },

            notify(c) {
                // Notificare doar când tab-ul e ascuns (altfel e doar sunet/badge).
                if (!this.supportsNotif || Notification.permission !== 'granted' || !document.hidden) return;
                try {
                    new Notification('Sanctuary Live Chat', {
                        body: (c.sender_name || 'Vizitator') + ': ' + (c.last_message || ''),
                    });
                } catch (e) { /* notificările pot eșua silențios pe unele browsere */ }
            },

            poll() {
                fetch({{ json_encode(route('admin.chat.conversations')) }}, { headers: { 'Accept': 'application/json' } })
                    .then(r => r.ok ? r.json() : null)
                    .then(data => {
                        if (!data) return;
                        const convs = data.conversations || [];

                        let needSound = false;
                        convs.forEach(c => {
                            const prev = this.booted ? (this.prevUnread[c.session_id] || 0) : c.unread;
                            if (c.unread > prev) {
                                // Sunet: mesaje în ALTE conversații (sau pe pagina de listă,
                                // unde current = null). Pentru conversația deschisă sună
                                // pagina de conversație — evităm dublarea sunetului.
                                if (this.current === null || c.session_id !== this.current) {
                                    needSound = true;
                                }
                                this.notify(c);
                            }
                            this.prevUnread[c.session_id] = c.unread;
                        });

                        if (needSound && this.booted && typeof window.sanctuaryDing === 'function') {
                            window.sanctuaryDing();
                        }

                        this.convs = convs;
                        this.total = data.total_unread || 0;
                        this.booted = true;
                    })
                    .catch(() => {});
            },

            init() {
                this.poll();
                setInterval(() => this.poll(), 4000);
            },
        };
    }
</script>

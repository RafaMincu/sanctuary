@extends('layouts.sanctuary')

@section('title', 'Conversație cu ' . $senderName . ' | The Sanctuary')

@section('content')
    <section class="relative z-10 max-w-3xl mx-auto px-6 pt-10 pb-20"
             x-data="adminChat('{{ $sessionId }}')">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.chat.index') }}"
                   class="w-9 h-9 shrink-0 rounded-sm border border-zinc-800 flex items-center justify-center text-zinc-400 hover:text-cyan-400 hover:border-cyan-500/40 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-cyan-400 to-emerald-400 flex items-center justify-center text-black font-bold text-sm">
                    {{ strtoupper(substr($senderName, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-white font-bold leading-none">{{ $senderName }}</h1>
                    <p class="text-emerald-400 text-xs mt-1 font-mono">
                        ● Vizitator activ <span class="text-zinc-600">// {{ $sessionId }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" form="logout-form" class="px-3 py-2 border border-zinc-800 text-zinc-400 text-[10px] font-bold uppercase tracking-wider hover:border-red-500/50 hover:text-red-300 transition-colors rounded-sm">
                    Deconectare
                </button>
                <form id="logout-form" action="{{ route('admin.chat.logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 rounded-sm border border-emerald-500/40 bg-emerald-950/20 text-emerald-300 text-xs backdrop-blur-md flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Messages Panel -->
        <div class="bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm backdrop-blur-2xl shadow-2xl flex flex-col"
             style="min-height: 420px;">

            {{-- Panel minimizat --}}
            <div x-show="minimized"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="minimized-chat absolute bottom-6 left-6 z-50 w-80 bg-[#0b0b0e] border border-zinc-900 rounded-sm shadow-2xl overflow-hidden"
                 style="display: flex; flex-direction: column; max-height: 320px;">
                <div class="flex items-center justify-between px-4 py-2 border-b border-zinc-900">
                    <span class="text-[10px] font-mono uppercase tracking-wider text-zinc-500">
                        // Minimizat
                    </span>
                    <span class="text-[10px] font-mono text-emerald-400">
                        {{ $senderName }}
                    </span>
                </div>
                <div class="flex-1 overflow-y-auto p-3 space-y-2" style="max-height: 240px;">
                    <template x-if="messages.length === 0">
                        <div class="text-center py-8">
                            <p class="text-zinc-500 text-xs">Nicio conversație deschisă.</p>
                        </div>
                    </template>
                    <template x-for="msg in messages" :key="msg.id">
                        <div class="text-xs text-zinc-300" x-text="msg.message"></div>
                    </template>
                </div>
            </div>

            {{-- Header bar --}}
            <div class="flex items-center justify-between px-5 py-3 border-b border-zinc-900" style="background: linear-gradient(135deg, rgba(34,211,238,0.08), rgba(52,211,153,0.05));">
                <span class="text-[10px] font-mono uppercase tracking-widest text-zinc-500">// Conversație Live</span>
                <span class="text-[10px] font-mono text-emerald-400" x-text="fetching ? '● sincronizare...' : '● actualizat'"></span>
            </div>

            {{-- Messages --}}
            <div x-ref="messageBox" class="flex-1 overflow-y-auto p-5 space-y-3" style="max-height: 440px; scrollbar-width: thin; scrollbar-color: rgba(34,211,238,0.2) transparent;">
                <template x-if="messages.length === 0">
                    <div class="text-center py-10">
                        <p class="text-zinc-500 text-sm">Niciun mesaj în această conversație.</p>
                    </div>
                </template>

                <template x-for="msg in messages" :key="msg.id">
                    {{-- from_user = true → mesaj de la vizitator (stânga), false → răspuns admin (dreapta) --}}
                    <div :class="msg.from_user ? 'flex justify-start' : 'flex justify-end'">
                        <div class="max-w-[78%]">
                            <div :class="msg.from_user
                                ? 'rounded-2xl rounded-bl-sm bg-zinc-900 border border-zinc-800 text-zinc-200'
                                : 'rounded-2xl rounded-br-sm text-black'"
                                 :style="msg.from_user ? '' : 'background: linear-gradient(135deg, #22d3ee, #34d399);'"
                                 class="px-4 py-2.5">
                                <p class="text-[10px] font-mono uppercase tracking-wider mb-1"
                                   :class="msg.from_user ? 'text-cyan-400' : 'text-black/60'"
                                   x-text="msg.sender_name"></p>
                                <p x-text="msg.message" class="text-sm leading-snug whitespace-pre-wrap"></p>
                                <p class="text-[10px] mt-1 text-right opacity-60 flex items-center justify-end gap-1"
                                   :class="msg.from_user ? 'text-zinc-500' : 'text-black/60'">
                                    {{-- Read receipt: doar pe răspunsurile proprii (from_user = false) --}}
                                    <span x-show="!msg.from_user"
                                          x-text="msg.read_at ? '✓✓' : '✓'"
                                          :class="msg.read_at ? 'opacity-100 font-bold' : 'opacity-70'"></span>
                                    <span x-text="msg.time"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Reply Form --}}
            <div class="border-t border-zinc-900 p-4" style="background: rgba(6,6,8,0.6);">
                <form @submit.prevent="sendReply()" class="flex items-end gap-2">
                    <textarea
                        x-model="newMessage"
                        @input="lastTypeAt = Date.now()"
                        @keydown.enter.prevent="if (!$event.shiftKey) { sendReply(); }"
                        rows="1"
                        placeholder="Scrie un răspuns... (Enter pentru trimitere, Shift+Enter pentru rând nou)"
                        class="flex-1 bg-[#060608] border border-zinc-800 text-zinc-200 text-sm px-4 py-3 rounded-sm placeholder-zinc-600 focus:outline-none focus:border-cyan-400 resize-none"></textarea>
                    <button type="submit"
                            :disabled="sending || !newMessage.trim()"
                            :class="newMessage.trim() && !sending
                                ? 'bg-gradient-to-r from-cyan-400 to-emerald-400 text-black shadow-[0_0_15px_rgba(34,211,238,0.3)]'
                                : 'bg-zinc-900 text-zinc-600 cursor-not-allowed'"
                            class="px-5 py-3 text-xs font-black uppercase tracking-widest rounded-sm transition-all">
                        <span x-show="!sending">Trimite</span>
                        <span x-show="sending">...</span>
                    </button>
                </form>
                @if ($errors->has('message'))
                    <p class="text-red-400 text-xs mt-2 font-mono">{{ $errors->first('message') }}</p>
                @endif
            </div>
        </div>

        <p class="text-center text-zinc-600 text-[11px] font-mono mt-5">
            Mesajele se actualizează automat la fiecare 2.5 secunde
        </p>
    </section>

    <script>
        function adminChat(sessionId) {
            return {
                messages: [],
                newMessage: '',
                sending: false,
                fetching: false,
                guestTyping: false,
                lastId: 0,
                booted: false,
                lastTypeAt: 0,

                minimize() {
                    const box = document.querySelector('.minimized-chat');
                    if (box) {
                        if (box.classList.contains('hidden')) {
                            box.classList.remove('hidden');
                            box.classList.add('flex');
                        } else {
                            box.classList.add('hidden');
                            box.classList.remove('flex');
                        }
                    }
                },

                fetchMessages() {
                    this.fetching = true;
                    const box = this.$refs.messageBox;
                    const nearBottom = box ? (box.scrollHeight - box.scrollTop - box.clientHeight < 80) : true;

                    let url = @json(route('admin.chat.messages', $sessionId));
                    // „Vizitatorul scrie..." doar la max. 3s după ultima tastare.
                    if (this.newMessage.trim() && Date.now() - this.lastTypeAt < 3000) {
                        url += (url.includes('?') ? '&' : '?') + 'typing=1';
                    }

                    fetch(url, {
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                    })
                    .then(r => r.json())
                    .then(data => {
                        const msgs = data.messages || [];
                        // Sunet la mesaj nou dinspre vizitator.
                        const newFromGuest = msgs.filter(m => m.from_user && m.id > this.lastId);
                        if (this.booted && newFromGuest.length && typeof window.sanctuaryDing === 'function') {
                            window.sanctuaryDing();
                        }
                        this.lastId = msgs.reduce((mx, m) => Math.max(mx, m.id), this.lastId);
                        this.booted = true;
                        this.messages = msgs;
                        this.guestTyping = !!data.guest_typing;
                        this.$nextTick(() => {
                            if (box && nearBottom) box.scrollTop = box.scrollHeight;
                        });
                    })
                    .catch(() => {})
                    .finally(() => { this.fetching = false; });
                },

                sendReply() {
                    const text = this.newMessage.trim();
                    if (!text || this.sending) return;
                    this.sending = true;

                    fetch(@json(route('admin.chat.reply', $sessionId)), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({ message: text })
                    })
                    .then(r => {
                        if (r.ok) { this.newMessage = ''; this.fetchMessages(); }
                        return r.json();
                    })
                    .catch(() => {})
                    .finally(() => { this.sending = false; });
                },

                init() {
                    this.fetchMessages();
                    setInterval(() => this.fetchMessages(), 2500);
                }
            };
        }
    </script>
@endsection

<div
    x-data="{
        open: false,
        messages: [],
        newMessage: '',
        loading: false,
        unread: 0,
        booted: false,
        lastId: 0,
        lastTypeAt: 0,

        init() { if (window.sanctuaryChat) window.sanctuaryChat.attach(this); },

        fetchMessages() {
            const params = [];
            if (this.open) params.push('open=1');
            if (this.open && this.newMessage.trim() && Date.now() - this.lastTypeAt < 3000) params.push('typing=1');
            const qs = params.length ? '?' + params.join('&') : '';
            fetch('{{ route('chat.messages') }}' + qs, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
            })
            .then(r => r.json())
            .then(data => {
                const msgs = data.messages || [];
                if (this.booted && msgs.length && typeof window.sanctuaryDing === 'function' && msgs.some(m => !m.from_user && m.id > this.lastId)) window.sanctuaryDing();
                this.lastId = msgs.reduce((mx, m) => Math.max(mx, m.id), this.lastId);
                this.messages = msgs;
                this.unread = data.unread || 0;
                this.$nextTick(() => {
                    const box = this.$refs.messageBox;
                    if (box) box.scrollTop = box.scrollHeight;
                });
            });
        },

        sendMessage() {
            if (!this.newMessage.trim() || this.loading) return;
            this.loading = true;
            fetch('{{ route('chat.message.store') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ message: this.newMessage })
            })
            .then(r => r.json())
            .then(() => { this.newMessage = ''; this.loading = false; this.fetchMessages(); })
            .catch(() => { this.loading = false; });
        },

        toggle() { this.open = !this.open; if (this.open) this.fetchMessages(); }
    }"
    x-init="init()"
    id="visitor-chat"
>
    {{-- Fereastra de chat --}}
<script>
    // Notificare sonoră pentru mesaje noi (Web Audio API — fără fișiere audio).
    (function () {
        var audioCtx = null;
        function getAudioCtx() {
            if (!audioCtx) {
                var Ctor = window.AudioContext || window.webkitAudioContext;
                if (!Ctor) return null;
                audioCtx = new Ctor();
            }
            if (audioCtx.state === 'suspended') {
                audioCtx.resume().catch(function () {});
            }
            return audioCtx;
        }
        // Politica autoplay: deblocăm contextul la prima interacțiune a utilizatorului.
        document.addEventListener('pointerdown', function () { getAudioCtx(); }, { once: true });
        document.addEventListener('keydown', function () { getAudioCtx(); }, { once: true });

        window.sanctuaryDing = function () {
            try {
                var ac = getAudioCtx();
                if (!ac || ac.state !== 'running') return;
                [880, 1318.5].forEach(function (freq, i) {
                    var start = ac.currentTime + i * 0.12;
                    var osc = ac.createOscillator();
                    var gain = ac.createGain();
                    osc.type = 'sine';
                    osc.frequency.value = freq;
                    gain.gain.setValueAtTime(0.0001, start);
                    gain.gain.exponentialRampToValueAtTime(0.15, start + 0.02);
                    gain.gain.exponentialRampToValueAtTime(0.0001, start + 0.35);
                    osc.connect(gain);
                    gain.connect(ac.destination);
                    osc.start(start);
                    osc.stop(start + 0.4);
                });
            } catch (e) { /* sunet indisponibil — ignorăm */ }
        };
    })();
</script>
<div id="sanctuary-chat"
     x-data="{
        open: false,
        messages: [],
        newMessage: '',
        loading: false,
        unread: 0,
        adminTyping: false,
        adminOnline: {{ Cache::get('chat:admin_online') ? 'true' : 'false' }},
        lastId: 0,
        booted: false,
        lastTypeAt: 0,
        fetchMessages() {
            const params = [];
            if (this.open) params.push('open=1');
            // „scrie...” doar la max. 3s după ultima tastare (nu cât stă textul în input).
            if (this.open && this.newMessage.trim() && Date.now() - this.lastTypeAt < 3000) params.push('typing=1');
            const qs = params.length ? '?' + params.join('&') : '';
            fetch('{{ route('chat.messages') }}' + qs, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
            })
            .then(r => r.json())
            .then(data => {
                const msgs = data.messages || [];
                const newFromAdmin = msgs.filter(m => !m.from_user && m.id > this.lastId);
                if (this.booted && newFromAdmin.length && typeof window.sanctuaryDing === 'function') {
                    window.sanctuaryDing();
                }
                this.lastId = msgs.reduce((mx, m) => Math.max(mx, m.id), this.lastId);
                this.booted = true;
                this.messages = msgs;
                this.unread = data.unread || 0;
                this.adminTyping = !!data.admin_typing;
                this.adminOnline = !!data.admin_online;
                this.$nextTick(() => { const el = this.$refs.messageBox; if (el) el.scrollTop = el.scrollHeight; });
            })
            .catch(() => {});
        },
        sendMessage() {
            const text = this.newMessage.trim();
            if (!text || this.loading) return;
            this.newMessage = '';
            this.loading = true;
            fetch('{{ route('chat.message.store') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ message: text })
            })
            .then(r => {
                if (!r.ok) throw new Error('Send failed');
                return r.json();
            })
            .then(data => { this.messages.push(data); this.$nextTick(() => { const el = this.$refs.messageBox; if (el) el.scrollTop = el.scrollHeight; }); })
            .catch(() => { this.newMessage = text; })
            .finally(() => { this.loading = false; });
        },
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.unread = 0;
                this.fetchMessages(); // marchează mesajele admin citite pe server
            }
        },
        init() {
            this.fetchMessages();
            setInterval(() => this.fetchMessages(), 2000);
        }
     }"
     class="fixed bottom-6 right-6 z-[9999] flex flex-col items-end gap-3">

    {{-- Chat Panel --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="w-80 flex flex-col rounded-2xl overflow-hidden shadow-2xl"
         style="background: rgba(10,10,15,0.85); backdrop-filter: blur(20px); border: 1px solid rgba(34,211,238,0.15); max-height: 480px;">

        {{-- Header --}}
        <div class="flex items-center gap-3 px-4 py-3" style="background: linear-gradient(135deg, rgba(34,211,238,0.12), rgba(52,211,153,0.08)); border-bottom: 1px solid rgba(34,211,238,0.1);">
            <div class="relative">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-emerald-400 flex items-center justify-center text-black font-bold text-xs">S</div>
                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full border-2 border-[#0a0a0f] status-live transition-colors duration-300" :class="adminOnline ? 'bg-emerald-400' : 'bg-zinc-600'"></span>
            </div>
            <div class="flex-1">
                <p class="text-white text-sm font-semibold leading-none">Sanctuary Support</p>
                <p class="text-xs mt-0.5 transition-colors duration-300"
                   :class="adminOnline ? 'text-emerald-400' : 'text-zinc-500'"
                   x-text="adminOnline ? '● Online' : '● Offline'">● Offline</p>
            </div>
            <button @click="open = false" class="text-zinc-500 hover:text-white transition-colors p-1" title="Minimizează">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
            </button>
        </div>

        {{-- Indicator: adminul scrie... --}}
        <div x-show="adminTyping"
             x-transition:enter="transition ease-out duration-150"
             x-transition:leave="transition ease-in duration-150"
             class="px-4 pt-2 text-[11px] text-emerald-300 italic animate-pulse">
            Sanctuary Support scrie...
        </div>

        {{-- Messages --}}
        <div x-ref="messageBox" class="flex-1 overflow-y-auto p-4 space-y-3" style="min-height: 280px; max-height: 320px; scrollbar-width: thin; scrollbar-color: rgba(34,211,238,0.2) transparent;">
            <template x-if="messages.length === 0">
                <div class="text-center py-8">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-500/20 to-emerald-500/20 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <p class="text-zinc-400 text-xs">Bună ziua! Cum te putem ajuta?</p>
                </div>
            </template>
            <template x-for="msg in messages" :key="msg.id">
                <div :class="msg.from_user ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="msg.from_user
                            ? 'bg-gradient-to-r from-cyan-500 to-cyan-400 text-black rounded-2xl rounded-br-sm'
                            : 'text-zinc-200 rounded-2xl rounded-bl-sm'"
                         :style="msg.from_user ? '' : 'background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.08);'"
                         class="max-w-[75%] px-3 py-2">
                        <p x-text="msg.message" class="text-sm leading-snug"></p>
                        <p class="text-xs opacity-60 mt-1 text-right flex items-center justify-end gap-1">
                            {{-- Read receipt: doar pe mesajele proprii (from_user) --}}
                            <span x-show="msg.from_user"
                                  x-text="msg.read_at ? '✓✓' : '✓'"
                                  :class="msg.read_at ? 'opacity-100 font-bold' : 'opacity-70'"></span>
                            <span x-text="msg.time"></span>
                        </p>
                    </div>
                </div>
            </template>
            <template x-if="loading">
                <div class="flex justify-start">
                    <div class="px-4 py-2 rounded-2xl rounded-bl-sm" style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.08);">
                        <div class="flex gap-1 items-center h-4">
                            <span class="w-1.5 h-1.5 bg-zinc-400 rounded-full animate-bounce" style="animation-delay:0s"></span>
                            <span class="w-1.5 h-1.5 bg-zinc-400 rounded-full animate-bounce" style="animation-delay:0.15s"></span>
                            <span class="w-1.5 h-1.5 bg-zinc-400 rounded-full animate-bounce" style="animation-delay:0.3s"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Input --}}
        <div style="border-top: 1px solid rgba(34,211,238,0.1); padding: 12px;">
            <form @submit.prevent="sendMessage()" class="flex items-center gap-2">
                <input
                    type="text"
                    x-model="newMessage"
                    @input="lastTypeAt = Date.now()"
                    @keydown.enter.prevent="sendMessage()"
                    placeholder="Scrie un mesaj..."
                    maxlength="1000"
                    class="flex-1 bg-transparent text-white text-sm placeholder-zinc-500 focus:outline-none"
                />
                <button type="submit"
                        :disabled="loading || !newMessage.trim()"
                        class="w-8 h-8 rounded-full flex items-center justify-center transition-all"
                        :class="newMessage.trim() ? 'bg-gradient-to-br from-cyan-400 to-emerald-400 text-black shadow-[0_0_12px_rgba(34,211,238,0.4)]' : 'bg-zinc-800 text-zinc-500 cursor-not-allowed'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Floating Action Button --}}
    <button @click="toggle()"
            class="relative w-14 h-14 rounded-full flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 focus:outline-none"
            style="background: linear-gradient(135deg, #22d3ee, #34d399); box-shadow: 0 0 20px rgba(34,211,238,0.35), 0 4px 15px rgba(0,0,0,0.5);"
            title="Chat Live">
        <svg x-show="!open" class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <svg x-show="open" class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
        <span x-show="unread > 0 && !open" x-text="unread" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold shadow"></span>
        <span class="absolute inset-0 rounded-full animate-ping opacity-20" style="background: #22d3ee;"></span>
    </button>
</div>
</div>

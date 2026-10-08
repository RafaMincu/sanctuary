@extends('layouts.sanctuary')

@section('title', 'Confirmare Programare ' . $booking->booking_number . ' | The Sanctuary')

@section('orbs')
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden h-[120vh]">
        <div class="orb absolute -top-16 left-1/2 -translate-x-1/2 h-80 w-80 rounded-full bg-{{ $booking->wing_color }}-500/20 blur-3xl"></div>
    </div>
@endsection

@section('content')
    <section class="relative z-10 max-w-3xl mx-auto px-6 pt-12 pb-16">
        @if (session('success'))
            <div class="mb-8 p-4 rounded-sm border border-emerald-500/40 bg-emerald-950/30 text-emerald-300 text-xs backdrop-blur-md flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="reveal bg-[#0b0b0e]/85 border border-zinc-900 rounded-sm backdrop-blur-2xl p-6 sm:p-10 shadow-2xl relative">
            <div class="absolute top-0 left-0 right-0 h-1 bg-{{ $booking->wing_color }}-400"></div>

            <!-- Header and Reference Number -->
            <div class="text-center pb-6 border-b border-zinc-900">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-widest bg-zinc-900 border border-zinc-800 text-zinc-400 mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-{{ $booking->wing_color }}-400"></span>
                    {{ $booking->wing_label }}
                </div>
                <h1 class="text-2xl sm:text-3xl font-black uppercase text-white tracking-wide mb-2">Programare Înregistrată</h1>
                <p class="text-zinc-400 text-xs font-mono">COD UNIC DE REFERINȚĂ / VERIFICARE</p>
                
                <div class="mt-4 inline-flex items-center gap-3 px-5 py-2.5 rounded-sm bg-zinc-950 border border-zinc-800">
                    <span id="booking-code" class="text-xl sm:text-2xl font-mono font-bold tracking-widest text-{{ $booking->wing_color }}-400">
                        {{ $booking->booking_number }}
                    </span>
                    <button type="button" onclick="copyCode()" class="text-zinc-500 hover:text-white transition-colors text-xs font-mono uppercase p-1">
                        [Copiază]
                    </button>
                </div>
                <div id="copy-feedback" class="text-[10px] font-mono text-emerald-400 mt-1 hidden">Cod copiat în clipboard!</div>
            </div>

            <!-- Status Indicator -->
            <div class="py-6 border-b border-zinc-900 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <div class="text-[11px] font-mono uppercase text-zinc-500 tracking-wider">Status Solicitare</div>
                    <div class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2 mt-1">
                        <span class="hud-dot inline-block h-2 w-2 rounded-full bg-{{ $booking->status_color }}-400"></span>
                        <span class="text-{{ $booking->status_color }}-400">{{ $booking->status_label }}</span>
                    </div>
                </div>

                <div class="text-center sm:text-right">
                    <div class="text-[11px] font-mono uppercase text-zinc-500 tracking-wider">Data & Ora Programată</div>
                    <div class="text-sm font-bold text-white mt-1 font-mono">
                        {{ $booking->scheduled_at->format('d.m.Y // H:i') }}
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="py-6 border-b border-zinc-900 space-y-4">
                <div class="text-xs font-mono uppercase tracking-wider text-zinc-400">// Detalii Client & Serviciu</div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono">
                    <div class="p-3 bg-zinc-950/60 border border-zinc-900 rounded-sm">
                        <span class="text-zinc-500 block text-[10px] uppercase">Client:</span>
                        <span class="text-zinc-200 font-bold text-sm">{{ $booking->client_name }}</span>
                    </div>

                    <div class="p-3 bg-zinc-950/60 border border-zinc-900 rounded-sm">
                        <span class="text-zinc-500 block text-[10px] uppercase">Telefon Contact:</span>
                        <span class="text-zinc-200 font-bold text-sm">{{ $booking->client_phone }}</span>
                    </div>

                    <div class="p-3 bg-zinc-950/60 border border-zinc-900 rounded-sm">
                        <span class="text-zinc-500 block text-[10px] uppercase">Email:</span>
                        <span class="text-zinc-200">{{ $booking->client_email }}</span>
                    </div>

                    <div class="p-3 bg-zinc-950/60 border border-zinc-900 rounded-sm">
                        <span class="text-zinc-500 block text-[10px] uppercase">Tip Serviciu:</span>
                        <span class="text-{{ $booking->wing_color }}-400 font-bold uppercase">{{ $booking->service_type }}</span>
                    </div>
                </div>

                @if (!empty($booking->metadata))
                    <div class="p-3 bg-zinc-950/80 border border-zinc-900 rounded-sm text-xs font-mono">
                        <span class="text-zinc-500 block text-[10px] uppercase mb-1">Informații Specifice Aripă:</span>
                        <ul class="space-y-1 text-zinc-300">
                            @foreach ($booking->metadata as $key => $val)
                                @if (!empty($val))
                                    <li><span class="text-zinc-500 capitalize">{{ str_replace('_', ' ', $key) }}:</span> {{ $val }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($booking->notes)
                    <div class="p-3 bg-zinc-950/60 border border-zinc-900 rounded-sm text-xs font-mono">
                        <span class="text-zinc-500 block text-[10px] uppercase mb-1">Note Client:</span>
                        <p class="text-zinc-300 leading-relaxed font-sans">{{ $booking->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Arrival instructions -->
            <div class="py-6 border-b border-zinc-900 text-xs text-zinc-400 space-y-2">
                <div class="font-bold uppercase tracking-wider text-zinc-300 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-{{ $booking->wing_color }}-400 rounded-full"></span>
                    Instrucțiuni Sosire la The Sanctuary:
                </div>
                <p class="leading-relaxed">
                    Te rugăm să te prezinți cu aproximativ 5-10 minute înainte de ora stabilită la recepția aferentă aripii alese. Prezintă codul de verificare <strong>{{ $booking->booking_number }}</strong> personalului de la intrare.
                </p>
            </div>

            <!-- Action buttons -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ route('bookings.lookup') }}" class="text-xs font-mono uppercase tracking-wider text-zinc-400 hover:text-white transition-colors">
                    &larr; Caută altă rezervare
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('bookings.create') }}" class="px-4 py-2 border border-zinc-800 text-xs font-bold uppercase tracking-wider text-zinc-300 hover:border-zinc-700 transition-colors">
                        Programare Nouă
                    </a>
                    <a href="{{ url('/') }}" class="px-5 py-2 bg-zinc-100 text-black text-xs font-bold uppercase tracking-wider hover:bg-white transition-colors">
                        Acasă
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        function copyCode() {
            const code = document.getElementById('booking-code').innerText;
            navigator.clipboard.writeText(code).then(() => {
                const feedback = document.getElementById('copy-feedback');
                feedback.classList.remove('hidden');
                setTimeout(() => {
                    feedback.classList.add('hidden');
                }, 3000);
            });
        }
    </script>
@endsection

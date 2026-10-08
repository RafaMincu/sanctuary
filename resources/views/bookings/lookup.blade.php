@extends('layouts.sanctuary')

@section('title', 'Verificare Status Programare | The Sanctuary')

@section('orbs')
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden h-[120vh]">
        <div class="orb absolute -top-16 left-1/2 -translate-x-1/2 h-80 w-80 rounded-full bg-cyan-500/15 blur-3xl"></div>
    </div>
@endsection

@section('content')
    <section class="relative z-10 max-w-2xl mx-auto px-6 pt-12 pb-16">
        <!-- Header -->
        <div class="text-center mb-10">
            <p class="reveal text-zinc-500 text-xs uppercase tracking-[0.25em] mb-2 font-mono">// Verificare Status // Ecosistem Unificat</p>
            <h1 class="reveal reveal-d1 text-3xl md:text-4xl font-black tracking-wide uppercase text-white mb-3">
                Verifică <span class="bg-gradient-to-r from-cyan-400 to-emerald-400 bg-clip-text text-transparent">Programarea</span>
            </h1>
            <p class="reveal reveal-d2 text-zinc-400 text-sm font-light max-w-md mx-auto">
                Introdu codul unic de referință (format: SNC-XXXXXX) sau numărul de telefon pentru a afla statusul curent.
            </p>
        </div>

        <!-- Search Form -->
        <div class="reveal reveal-d3 bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm backdrop-blur-2xl p-6 sm:p-8 shadow-2xl mb-8">
            <form action="{{ route('bookings.lookup') }}" method="GET" class="space-y-4">
                <div>
                    <label for="code" class="block text-xs uppercase tracking-widest font-mono text-zinc-400 mb-1">Cod Programare</label>
                    <input type="text" id="code" name="code" value="{{ $code }}" placeholder="Ex: SNC-2A8B4C" class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-sm px-4 py-3 rounded-sm focus:outline-none focus:border-cyan-400 font-mono uppercase">
                </div>

                <div class="text-center text-xs font-mono text-zinc-600 uppercase">— SAU —</div>

                <div>
                    <label for="phone" class="block text-xs uppercase tracking-widest font-mono text-zinc-400 mb-1">Număr de Telefon</label>
                    <input type="tel" id="phone" name="phone" value="{{ $phone }}" placeholder="Ex: 07XXXXXXXX" class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-sm px-4 py-3 rounded-sm focus:outline-none focus:border-cyan-400 font-mono">
                </div>

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-cyan-400 to-emerald-400 text-black text-xs font-black uppercase tracking-widest hover:opacity-95 transition-opacity shadow-[0_0_15px_rgba(34,211,238,0.2)]">
                    Caută Programare
                </button>
            </form>
        </div>

        <!-- Results Section -->
        @if ($searched)
            @if ($booking)
                <div class="reveal p-6 bg-[#0b0b0e]/90 border border-zinc-800 rounded-sm backdrop-blur-xl">
                    <div class="flex items-center justify-between pb-4 border-b border-zinc-900">
                        <div>
                            <span class="text-[11px] font-mono text-zinc-500 uppercase">Cod:</span>
                            <span class="text-lg font-mono font-bold text-cyan-400 ml-1">{{ $booking->booking_number }}</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-mono uppercase tracking-wider bg-zinc-900 border border-zinc-800 text-{{ $booking->status_color }}-400">
                            {{ $booking->status_label }}
                        </span>
                    </div>

                    <div class="py-4 space-y-2 text-xs font-mono">
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Departament:</span>
                            <span class="text-zinc-300">{{ $booking->wing_label }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Serviciu:</span>
                            <span class="text-zinc-300 font-bold uppercase">{{ $booking->service_type }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Programat pentru:</span>
                            <span class="text-zinc-300 font-bold">{{ $booking->scheduled_at->format('d.m.Y // H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Nume Client:</span>
                            <span class="text-zinc-300">{{ $booking->client_name }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-900 flex justify-end">
                        <a href="{{ route('bookings.show', $booking->booking_number) }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-cyan-400 hover:underline">
                            Vezi Detaliile Complete &rarr;
                        </a>
                    </div>
                </div>
            @else
                <div class="reveal p-6 bg-[#0b0b0e]/90 border border-rose-900/30 rounded-sm text-center">
                    <div class="text-rose-400 text-xs font-mono uppercase tracking-wider mb-2">Nu a fost găsită nicio programare</div>
                    <p class="text-zinc-400 text-xs leading-relaxed max-w-sm mx-auto mb-4">
                        Te rugăm să verifici codul introdus sau numărul de telefon. Asigură-te că include prefixul complet.
                    </p>
                    <a href="{{ route('bookings.create') }}" class="inline-block px-4 py-2 border border-zinc-800 text-xs font-bold uppercase tracking-wider text-zinc-300 hover:border-zinc-700">
                        Creează o Programare Nouă
                    </a>
                </div>
            @endif
        @endif
    </section>
@endsection

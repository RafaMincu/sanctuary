@extends('layouts.sanctuary')

@section('title', 'Contul meu | The Sanctuary')

@section('content')
    <section class="relative z-10 max-w-5xl mx-auto px-6 pt-10 pb-20">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5 mb-8 reveal">
            <div>
                <p class="text-cyan-400/80 text-xs uppercase tracking-[0.25em] font-mono mb-2">// Sanctuary ID // Client Portal</p>
                <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-wide text-white">
                    Salut, <span class="bg-gradient-to-r from-cyan-400 to-emerald-400 bg-clip-text text-transparent">{{ auth()->user()->name }}</span>
                </h1>
                <p class="mt-2 text-sm text-zinc-400">Programările tale, într-un singur panou.</p>
            </div>
            <a href="{{ route('bookings.create') }}" class="inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-cyan-400 to-emerald-400 text-black text-xs font-black uppercase tracking-widest hover:opacity-90">
                + Programare nouă
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 flex items-center gap-2 border border-emerald-500/30 bg-emerald-950/20 p-4 text-xs text-emerald-300 rounded-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>{{ session('success') }}
            </div>
        @endif

        <div class="reveal reveal-d2 bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm backdrop-blur-xl overflow-hidden">
            <div class="flex items-center justify-between gap-4 px-5 py-4 border-b border-zinc-900">
                <div>
                    <h2 class="text-sm font-bold text-white uppercase tracking-wide">Programările mele</h2>
                    <p class="mt-1 text-[11px] font-mono text-zinc-600">{{ $bookings->count() }} înregistrări asociate contului</p>
                </div>
                <span class="px-2 py-1 border border-cyan-400/20 bg-cyan-950/20 text-[10px] font-mono uppercase text-cyan-300">{{ auth()->user()->email }}</span>
            </div>

            <div class="divide-y divide-zinc-900">
                @forelse ($bookings as $booking)
                    <a href="{{ route('bookings.show', $booking->booking_number) }}" class="block px-5 py-4 hover:bg-cyan-500/5 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-5">
                            <div class="min-w-[110px] text-[11px] font-mono text-cyan-400">{{ $booking->booking_number }}</div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-white">{{ $booking->wing_label }}</p>
                                <p class="mt-1 text-xs text-zinc-500">{{ $booking->scheduled_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                            <span class="shrink-0 px-2.5 py-1 rounded-sm border text-[10px] font-mono uppercase {{ $booking->status === 'confirmed' ? 'border-emerald-500/30 bg-emerald-950/20 text-emerald-400' : ($booking->status === 'completed' ? 'border-cyan-500/30 bg-cyan-950/20 text-cyan-400' : ($booking->status === 'cancelled' ? 'border-rose-500/30 bg-rose-950/20 text-rose-400' : 'border-yellow-500/30 bg-yellow-950/20 text-yellow-400')) }}">
                                {{ $booking->status_label }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-14 text-center">
                        <p class="text-sm text-zinc-400">Nu ai încă programări asociate acestui cont.</p>
                        <p class="mt-2 text-xs font-mono text-zinc-600">Prima rezervare va apărea aici automat.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
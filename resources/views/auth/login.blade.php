@extends('layouts.sanctuary')

@section('title', 'Autentificare | The Sanctuary')

@section('content')
    <section class="relative z-10 max-w-md mx-auto px-6 pt-14 pb-20">
        <div class="reveal text-center mb-8">
            <p class="text-cyan-400/80 text-xs uppercase tracking-[0.25em] font-mono mb-3">// Acces Client // Sanctuary ID</p>
            <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-wide text-white">
                Bine ai <span class="bg-gradient-to-r from-cyan-400 to-emerald-400 bg-clip-text text-transparent">revenit</span>
            </h1>
            <p class="mt-3 text-sm text-zinc-400 font-light">Intră în cont pentru a-ți vedea programările și conversațiile.</p>
        </div>

        <div class="reveal reveal-d2 bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm backdrop-blur-xl shadow-2xl overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-cyan-400 to-emerald-400"></div>
            <form action="{{ route('login.store') }}" method="POST" class="p-6 sm:p-8 space-y-5">
                @csrf

                @if ($errors->any())
                    <div class="border border-rose-500/30 bg-rose-950/20 px-3 py-3 text-xs text-rose-300 rounded-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label for="email" class="block mb-1.5 text-[11px] font-mono uppercase tracking-widest text-zinc-400">Adresă email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                           class="w-full rounded-sm border border-zinc-800 bg-[#060608] px-4 py-3 text-sm text-zinc-100 outline-none transition-colors focus:border-cyan-400">
                </div>

                <div>
                    <label for="password" class="block mb-1.5 text-[11px] font-mono uppercase tracking-widest text-zinc-400">Parolă</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                           class="w-full rounded-sm border border-zinc-800 bg-[#060608] px-4 py-3 text-sm text-zinc-100 outline-none transition-colors focus:border-cyan-400">
                </div>

                <label class="flex items-center gap-2 text-xs text-zinc-400 cursor-pointer">
                    <input type="checkbox" name="remember" value="1" class="rounded border-zinc-700 bg-zinc-900 text-cyan-400 focus:ring-cyan-400">
                    Menține sesiunea activă pe acest dispozitiv
                </label>

                <button type="submit" class="w-full px-5 py-3.5 bg-gradient-to-r from-cyan-400 to-emerald-400 text-black text-xs font-black uppercase tracking-widest transition-opacity hover:opacity-90">
                    Intră în cont &rarr;
                </button>
            </form>

            <div class="border-t border-zinc-900 px-6 sm:px-8 py-4 text-center text-xs text-zinc-500">
                Nu ai încă un cont?
                <a href="{{ route('register') }}" class="ml-1 font-bold text-cyan-400 hover:text-cyan-300">Creează Sanctuary ID</a>
            </div>
        </div>
    </section>
@endsection
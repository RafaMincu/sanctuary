@extends('layouts.sanctuary')

@section('title', 'Autentificare Admin Chat | The Sanctuary')

@section('orbs')
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden h-[120vh]">
        <div class="orb absolute -top-16 left-1/2 -translate-x-1/2 h-80 w-80 rounded-full bg-cyan-500/15 blur-3xl"></div>
    </div>
@endsection

@section('content')
    <section class="relative z-10 max-w-md mx-auto px-6 pt-16 pb-24">
        <!-- Header -->
        <div class="text-center mb-8">
            <p class="reveal text-zinc-500 text-xs uppercase tracking-[0.25em] mb-2 font-mono">// Acces Restricționat // Live Chat</p>
            <h1 class="reveal reveal-d1 text-3xl font-black tracking-wide uppercase text-white mb-3">
                Panou <span class="bg-gradient-to-r from-cyan-400 to-emerald-400 bg-clip-text text-transparent">Admin</span>
            </h1>
            <p class="reveal reveal-d2 text-zinc-400 text-sm font-light">
                Introdu emailul și parola pentru a accesa conversațiile live.
            </p>
        </div>

        <!-- Login Form -->
        <div class="reveal reveal-d3 bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm backdrop-blur-2xl p-6 sm:p-8 shadow-2xl">
            @if ($errors->any())
                <div class="mb-5 p-3 rounded-sm border border-red-500/40 bg-red-950/20 text-red-300 text-xs backdrop-blur-md">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.chat.login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs uppercase tracking-widest font-mono text-zinc-400 mb-1">Email Admin</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}" autofocus
                           placeholder="nume@sanctuary.ro"
                           class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-sm px-4 py-3 rounded-sm focus:outline-none focus:border-cyan-400 font-mono">
                </div>

                <div>
                    <label for="password" class="block text-xs uppercase tracking-widest font-mono text-zinc-400 mb-1">Parolă Admin</label>
                    <input type="password" id="password" name="password" required
                           placeholder="••••••••"
                           class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-sm px-4 py-3 rounded-sm focus:outline-none focus:border-cyan-400 font-mono">
                </div>

                <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-cyan-400 to-emerald-400 text-black text-xs font-black uppercase tracking-widest hover:opacity-95 transition-opacity shadow-[0_0_15px_rgba(34,211,238,0.2)]">
                    Intră în Panou
                </button>
            </form>
        </div>

        <p class="reveal reveal-d4 text-center text-zinc-600 text-[11px] font-mono mt-6">
            <a href="{{ route('home') }}" class="hover:text-cyan-400 transition-colors">← Înapoi pe site</a>
        </p>
    </section>
@endsection
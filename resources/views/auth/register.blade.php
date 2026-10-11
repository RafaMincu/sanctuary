@extends('layouts.sanctuary')

@section('title', 'Creează cont | The Sanctuary')

@section('content')
    <section class="relative z-10 max-w-md mx-auto px-6 pt-14 pb-20">
        <div class="reveal text-center mb-8">
            <p class="text-emerald-400/80 text-xs uppercase tracking-[0.25em] font-mono mb-3">// Înrolare Client // Sanctuary ID</p>
            <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-wide text-white">
                Creează-ți <span class="bg-gradient-to-r from-cyan-400 to-emerald-400 bg-clip-text text-transparent">contul</span>
            </h1>
            <p class="mt-3 text-sm text-zinc-400 font-light">Păstrează programările și accesul în același loc.</p>
        </div>

        <div class="reveal reveal-d2 bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm backdrop-blur-xl shadow-2xl overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-emerald-400 to-cyan-400"></div>
            <form action="{{ route('register.store') }}" method="POST" class="p-6 sm:p-8 space-y-5">
                @csrf

                <div>
                    <label for="name" class="block mb-1.5 text-[11px] font-mono uppercase tracking-widest text-zinc-400">Nume complet</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="w-full rounded-sm border border-zinc-800 bg-[#060608] px-4 py-3 text-sm text-zinc-100 outline-none transition-colors focus:border-emerald-400">
                    @error('name')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block mb-1.5 text-[11px] font-mono uppercase tracking-widest text-zinc-400">Adresă email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                           class="w-full rounded-sm border border-zinc-800 bg-[#060608] px-4 py-3 text-sm text-zinc-100 outline-none transition-colors focus:border-emerald-400">
                    @error('email')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block mb-1.5 text-[11px] font-mono uppercase tracking-widest text-zinc-400">Parolă</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="w-full rounded-sm border border-zinc-800 bg-[#060608] px-4 py-3 text-sm text-zinc-100 outline-none transition-colors focus:border-emerald-400">
                    <p class="mt-1.5 text-[11px] text-zinc-600">Minimum 8 caractere, o literă mare și un număr.</p>
                    @error('password')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block mb-1.5 text-[11px] font-mono uppercase tracking-widest text-zinc-400">Confirmă parola</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                           class="w-full rounded-sm border border-zinc-800 bg-[#060608] px-4 py-3 text-sm text-zinc-100 outline-none transition-colors focus:border-emerald-400">
                </div>

                <button type="submit" class="w-full px-5 py-3.5 bg-gradient-to-r from-cyan-400 to-emerald-400 text-black text-xs font-black uppercase tracking-widest transition-opacity hover:opacity-90">
                    Creează Sanctuary ID &rarr;
                </button>
            </form>

            <div class="border-t border-zinc-900 px-6 sm:px-8 py-4 text-center text-xs text-zinc-500">
                Ai deja cont?
                <a href="{{ route('login') }}" class="ml-1 font-bold text-emerald-400 hover:text-emerald-300">Autentifică-te</a>
            </div>
        </div>
    </section>
@endsection
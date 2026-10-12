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

                <div class="flex items-center gap-3 text-[10px] font-mono uppercase tracking-widest text-zinc-600">
                    <span class="h-px flex-1 bg-zinc-900"></span>
                    sau
                    <span class="h-px flex-1 bg-zinc-900"></span>
                </div>

                <a href="{{ route('google.redirect') }}"
                   class="w-full flex items-center justify-center gap-3 px-5 py-3.5 border border-zinc-800 bg-[#060608] text-zinc-300 text-xs font-bold uppercase tracking-widest transition-colors hover:border-zinc-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-emerald-400/50"
                   aria-label="Continuă cu Google">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continuă cu Google
                </a>
            </form>

            <div class="border-t border-zinc-900 px-6 sm:px-8 py-4 text-center text-xs text-zinc-500">
                Ai deja cont?
                <a href="{{ route('login') }}" class="ml-1 font-bold text-emerald-400 hover:text-emerald-300">Autentifică-te</a>
            </div>
        </div>
    </section>
@endsection
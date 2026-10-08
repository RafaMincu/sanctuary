@extends('layouts.sanctuary')

@section('title', 'The Iron Sanctuary Fitness | Sală')

@section('orbs')
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden h-[140vh]">
        <div class="orb absolute -top-16 right-[10%] h-80 w-80 rounded-full bg-blue-500/18 blur-3xl"></div>
        <div class="orb orb-delay absolute top-[40%] left-[8%] h-72 w-72 rounded-full bg-blue-400/10 blur-3xl"></div>
    </div>
@endsection

@section('content')
    <!-- Hero / Title -->
    <section class="relative z-10 max-w-4xl mx-auto px-6 pt-12 pb-6 text-center">
        <p class="reveal text-blue-400/80 text-xs uppercase tracking-[0.25em] mb-3 font-mono">[ Aripa Dreaptă ] // The Iron Sanctuary Fitness</p>
        <h1 class="reveal reveal-d1 text-4xl md:text-6xl font-black tracking-wide uppercase text-white mb-4">
            Sală <span class="relative inline-block">
                <span class="title-live pointer-events-none absolute inset-0 text-blue-400 blur-xl opacity-50" aria-hidden="true">Fitness</span>
                <span class="relative bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">Fitness</span>
            </span>
        </h1>
        <p class="reveal reveal-d2 text-zinc-400 text-sm md:text-base font-light leading-relaxed max-w-2xl mx-auto">
            Sala din dreapta complexului: forță, cardio și antrenori pe sală. Program de antrenament pentru cine vrea să se antreneze serios, nu un club de poză.
        </p>
    </section>

    <!-- Services Grid: Pure Tailwind Utilities -->
    <section class="relative z-10 max-w-7xl mx-auto px-6 mt-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <article class="reveal reveal-d2 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-blue-500 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-blue-500/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(59,130,246,0.15)]">
                <div class="text-blue-400 font-mono text-xs uppercase tracking-widest mb-3">01</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Forță</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Rack-uri, bare, discuri și zone de free weights. Lucru de bază: genuflexiuni, împins, tractări, rânduri.</p>
            </article>

            <article class="reveal reveal-d2 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-blue-500 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-blue-500/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(59,130,246,0.15)]">
                <div class="text-blue-400 font-mono text-xs uppercase tracking-widest mb-3">02</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Cardio</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Banda, bicicletă, stepper și intervale scurte. Încălzire înainte de forță sau sesiune separată.</p>
            </article>

            <article class="reveal reveal-d3 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-blue-500 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-blue-500/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(59,130,246,0.15)]">
                <div class="text-blue-400 font-mono text-xs uppercase tracking-widest mb-3">03</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Antrenori</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Antrenor pe sală: formă, progresie și corecții. Nu vinzi vise — îți spune ce să faci săptămâna asta.</p>
            </article>

            <article class="reveal reveal-d3 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-blue-500 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-blue-500/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(59,130,246,0.15)]">
                <div class="text-blue-400 font-mono text-xs uppercase tracking-widest mb-3">04</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Program</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Plan pe zile: volum, greutăți și recuperare. Pentru începători și pentru cine deja ridică serios.</p>
            </article>

            <article class="reveal reveal-d4 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-blue-500 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-blue-500/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(59,130,246,0.15)]">
                <div class="text-blue-400 font-mono text-xs uppercase tracking-widest mb-3">05</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Vestiare</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Dușuri, dulapuri și loc de schimbat. Intri, te antrenezi, pleci — fără circ de spa.</p>
            </article>

            <article class="reveal reveal-d4 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-blue-500 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-blue-500/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(59,130,246,0.15)]">
                <div class="text-blue-400 font-mono text-xs uppercase tracking-widest mb-3">06</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Complex</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">După antrenament poți mânca la restaurantul din zona centrală. Garajul e în aripa stângă, sala e în dreapta.</p>
            </article>
        </div>
    </section>

    <!-- Process Steps: Pure Tailwind Utilities -->
    <section class="relative z-10 max-w-7xl mx-auto px-6 mt-8 grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="reveal bg-[#0b0b0e]/70 border border-zinc-900 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:border-zinc-800">
            <div class="text-zinc-500 font-mono text-xs uppercase tracking-widest mb-2">Pasul 1</div>
            <h3 class="text-white font-extrabold uppercase tracking-wide mb-2">Te înscrii</h3>
            <p class="text-zinc-400 text-sm font-light leading-relaxed">Abonament sau ședințe. Spui nivelul tău și dacă vrei antrenor sau lucrezi singur.</p>
        </div>

        <div class="reveal reveal-d2 bg-[#0b0b0e]/70 border border-zinc-900 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:border-zinc-800">
            <div class="text-zinc-500 font-mono text-xs uppercase tracking-widest mb-2">Pasul 2</div>
            <h3 class="text-white font-extrabold uppercase tracking-wide mb-2">Primești planul</h3>
            <p class="text-zinc-400 text-sm font-light leading-relaxed">Îți arătăm sala, regulile de pe sală și un program de start. Ajustăm după cum răspunzi la antrenament.</p>
        </div>

        <div class="reveal reveal-d3 bg-[#0b0b0e]/70 border border-zinc-900 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:border-zinc-800">
            <div class="text-zinc-500 font-mono text-xs uppercase tracking-widest mb-2">Pasul 3</div>
            <h3 class="text-white font-extrabold uppercase tracking-wide mb-2">Te antrenezi</h3>
            <p class="text-zinc-400 text-sm font-light leading-relaxed">Intri pe aripa dreaptă, lucrezi și notezi progresul. Antrenorul e pe sală când ai nevoie de ochi extra.</p>
        </div>
    </section>

    <!-- Action CTA: Pure Tailwind Utilities -->
    <section class="relative z-10 max-w-7xl mx-auto px-6 mt-8">
        <div class="reveal border border-blue-500/20 bg-blue-950/10 p-8 md:p-10 rounded-sm flex flex-col md:flex-row md:items-center md:justify-between gap-6 backdrop-blur-xl">
            <div>
                <h2 class="text-white text-xl font-extrabold uppercase tracking-wide mb-2">Vrei în club?</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed max-w-xl">Treci pe la recepția sălii din aripa dreaptă. Îți spunem orarul, abonamentele și dacă e loc la antrenor.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0">
                <a href="{{ route('bookings.create', ['wing' => 'fitness']) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-blue-500 text-white text-xs font-black uppercase tracking-widest hover:bg-blue-400 transition-colors shadow-[0_0_15px_rgba(59,130,246,0.3)]">
                    Programează Sesiune &rarr;
                </a>
                <a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 border border-blue-400/40 text-blue-400 text-xs font-bold uppercase tracking-widest hover:bg-blue-400/10 transition-colors">
                    ← Înapoi
                </a>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.sanctuary')

@section('title', 'The Iron Sanctuary Auto | Garaj')

@section('orbs')
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden h-[140vh]">
        <div class="orb absolute -top-16 left-[10%] h-80 w-80 rounded-full bg-cyan-500/18 blur-3xl"></div>
        <div class="orb orb-delay absolute top-[40%] right-[8%] h-72 w-72 rounded-full bg-cyan-400/10 blur-3xl"></div>
    </div>
@endsection

@section('content')
    <section class="relative z-10 max-w-4xl mx-auto px-6 pt-12 pb-6 text-center">
        <p class="reveal text-cyan-400/80 text-xs uppercase tracking-[0.25em] mb-3 font-mono">[ Aripa Stângă ] // The Iron Sanctuary Auto</p>
        <h1 class="reveal reveal-d1 text-4xl md:text-6xl font-black tracking-wide uppercase text-white mb-4">
            Garaj <span class="relative inline-block">
                <span class="title-live pointer-events-none absolute inset-0 text-cyan-400 blur-xl opacity-50" aria-hidden="true">Auto</span>
                <span class="relative bg-gradient-to-r from-cyan-400 to-emerald-400 bg-clip-text text-transparent">Auto</span>
            </span>
        </h1>
        <p class="reveal reveal-d2 text-zinc-400 text-sm md:text-base font-light leading-relaxed max-w-2xl mx-auto">
            Atelierul din stânga complexului: mașini și motociclete pe elevator, diagnoze, revizii și reparații făcute de mecanici. Fără marketing de tuning „inteligent” — lucrări reale, pe banc, în hală.
        </p>
    </section>

    <section class="relative z-10 max-w-7xl mx-auto px-6 mt-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <article class="reveal reveal-d2 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-cyan-400 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-cyan-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(34,211,238,0.15)]">
                <div class="text-cyan-400 font-mono text-xs uppercase tracking-widest mb-3">01</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Diagnoză</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Citire erori, verificare senzori și identificare problemă înainte de a schimba piese la întâmplare.</p>
            </article>
            <article class="reveal reveal-d2 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-cyan-400 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-cyan-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(34,211,238,0.15)]">
                <div class="text-cyan-400 font-mono text-xs uppercase tracking-widest mb-3">02</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Revizii</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Ulei, filtre, verificări periodice după kilometraj sau după cartea mașinii.</p>
            </article>
            <article class="reveal reveal-d3 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-cyan-400 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-cyan-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(34,211,238,0.15)]">
                <div class="text-cyan-400 font-mono text-xs uppercase tracking-widest mb-3">03</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Reparații</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Mecanică generală: frâne, suspensie, distribuție, scurgeri, zgomote, piese uzate.</p>
            </article>
            <article class="reveal reveal-d3 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-cyan-400 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-cyan-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(34,211,238,0.15)]">
                <div class="text-cyan-400 font-mono text-xs uppercase tracking-widest mb-3">04</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Elevator</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Lucrări pe elevator, acces sub mașină, inspecție vizuală și montaj corect, nu „pe burtă în curte”.</p>
            </article>
            <article class="reveal reveal-d4 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-cyan-400 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-cyan-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(34,211,238,0.15)]">
                <div class="text-cyan-400 font-mono text-xs uppercase tracking-widest mb-3">05</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Moto</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Service și pentru motociclete: întreținere, reparații și verificări înainte de sezon sau de drum.</p>
            </article>
            <article class="reveal reveal-d4 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-cyan-400 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-cyan-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(34,211,238,0.15)]">
                <div class="text-cyan-400 font-mono text-xs uppercase tracking-widest mb-3">06</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Atelier</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Spațiu de lucru în aripa stângă a clădirii. Poți aștepta la restaurantul din centrul complexului cât se lucrează.</p>
            </article>
        </div>
    </section>

    <section class="relative z-10 max-w-7xl mx-auto px-6 mt-8 grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="reveal bg-[#0b0b0e]/70 border border-zinc-900 p-7 rounded-sm">
            <div class="text-zinc-500 font-mono text-xs uppercase tracking-widest mb-2">Pasul 1</div>
            <h3 class="text-white font-extrabold uppercase tracking-wide mb-2">Programezi</h3>
            <p class="text-zinc-400 text-sm font-light leading-relaxed">Spui ce mașină sau motocicletă ai și ce problemă observi. Îți fixăm un interval în atelier.</p>
        </div>
        <div class="reveal reveal-d2 bg-[#0b0b0e]/70 border border-zinc-900 p-7 rounded-sm">
            <div class="text-zinc-500 font-mono text-xs uppercase tracking-widest mb-2">Pasul 2</div>
            <h3 class="text-white font-extrabold uppercase tracking-wide mb-2">Aduci vehiculul</h3>
            <p class="text-zinc-400 text-sm font-light leading-relaxed">Intri pe aripa stângă, la garaj. Mecanicul confirmă lucrarea și îți spune ce se schimbă, înainte să înceapă.</p>
        </div>
        <div class="reveal reveal-d3 bg-[#0b0b0e]/70 border border-zinc-900 p-7 rounded-sm">
            <div class="text-zinc-500 font-mono text-xs uppercase tracking-widest mb-2">Pasul 3</div>
            <h3 class="text-white font-extrabold uppercase tracking-wide mb-2">Ridici gata</h3>
            <p class="text-zinc-400 text-sm font-light leading-relaxed">Primești explicația pe scurt: ce s-a făcut, ce merită urmărit și când e următoarea revizie.</p>
        </div>
    </section>

    <section class="relative z-10 max-w-7xl mx-auto px-6 mt-8">
        <div class="reveal border border-cyan-400/20 bg-cyan-950/10 p-8 md:p-10 rounded-sm flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h2 class="text-white text-xl font-extrabold uppercase tracking-wide mb-2">Vrei o programare?</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed max-w-xl">Treci pe la recepția garajului sau lasă un mesaj cu marca, modelul și ce se întâmplă cu mașina. Îți confirmăm locul în atelier.</p>
            </div>
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-5 py-3 border border-cyan-400/40 text-cyan-400 text-xs font-bold uppercase tracking-widest hover:bg-cyan-400/10 transition-colors">← Înapoi la complex</a>
        </div>
    </section>
@endsection

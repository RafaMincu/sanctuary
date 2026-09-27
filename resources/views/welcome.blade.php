@extends('layouts.sanctuary')

@section('title', 'THE SANCTUARY | Ultimate Lifestyle Ecosystem')

@section('orbs')
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden h-[140vh]">
        <div class="orb absolute -top-24 left-[8%] h-72 w-72 rounded-full bg-cyan-500/15 blur-3xl"></div>
        <div class="orb orb-delay absolute top-[28%] left-1/2 -translate-x-1/2 h-80 w-80 rounded-full bg-emerald-400/12 blur-3xl"></div>
        <div class="orb absolute top-[18%] right-[6%] h-72 w-72 rounded-full bg-blue-500/14 blur-3xl"></div>
    </div>
@endsection

@section('content')
    <section class="relative z-10 max-w-3xl mx-auto px-6 pt-12 pb-2 text-center">
        <p class="reveal text-zinc-500 text-xs uppercase tracking-[0.25em] mb-3 font-mono">// Ecosistem Unificat // Concept Est. 2026</p>
        <h1 class="reveal reveal-d1 text-4xl md:text-6xl font-black tracking-wide uppercase text-white mb-2">
            Welcome To <span class="relative inline-block">
                <span class="title-live pointer-events-none absolute inset-0 bg-gradient-to-r from-cyan-400 to-emerald-400 blur-xl opacity-50" aria-hidden="true">The Sanctuary</span>
                <span class="relative bg-gradient-to-r from-cyan-400 to-emerald-400 bg-clip-text text-transparent">The Sanctuary</span>
            </span>
        </h1>
        <p class="reveal reveal-d2 text-zinc-400 text-sm md:text-base font-light leading-relaxed max-w-xl mx-auto mt-2">
            Trei departamente de înaltă performanță reunite sub un singur acoperiș: mecanică de precizie, nutriție de calitate și antrenament dedicat.
        </p>
    </section>

    <!-- Visual Centerpiece -->
    <div class="max-w-7xl mx-auto px-6 my-6 relative z-10 reveal reveal-d2">
        <div class="frame-live group relative overflow-hidden bg-gradient-to-br from-[#0b0b0f] to-[#121217] p-1.5 rounded-lg border border-zinc-800 shadow-[0_20px_50px_rgba(0,0,0,0.9),0_0_15px_rgba(34,211,238,0.05)]">
            <img src="{{ asset('sanctuary-core.jpg') }}" alt="The Sanctuary Layout" class="w-full h-auto rounded-md block object-cover contrast-[1.02] brightness-[0.98] transition-transform duration-700 ease-out group-hover:scale-[1.015]">
            <div class="pointer-events-none absolute inset-1.5 overflow-hidden rounded-md">
                <div class="img-scan absolute top-0 h-full w-1/3 bg-gradient-to-r from-transparent via-cyan-300/15 to-transparent"></div>
            </div>
        </div>
    </div>

    <!-- Wing Highlights Grid -->
    <section class="relative z-10 max-w-7xl mx-auto px-6 mt-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Wing Left: Auto -->
            <div class="reveal reveal-d2 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-cyan-400 p-8 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-cyan-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(34,211,238,0.15)] group flex flex-col justify-between">
                <div>
                    <div class="text-cyan-400 font-mono text-xs uppercase tracking-widest mb-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-cyan-400 rounded-full"></span>
                        [ Aripa Stângă ]
                    </div>
                    <h3 class="text-white text-xl font-extrabold uppercase tracking-wide mb-3">The Iron Sanctuary<br>Auto</h3>
                    <p class="text-zinc-400 text-sm font-light leading-relaxed mb-6">Service auto și moto: diagnoze, revizii, reparații pe elevator și lucrări de atelier făcute de echipa de mecanici.</p>
                </div>
                <a href="{{ url('/garaj') }}" class="text-cyan-400 text-xs font-bold uppercase tracking-wider inline-flex items-center gap-2 transition-all duration-300 group-hover:underline group-hover:tracking-widest group-hover:gap-3">
                    Accesează Garajul &rarr;
                </a>
            </div>

            <!-- Wing Center: Food -->
            <div id="food" class="reveal reveal-d3 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-emerald-400 p-8 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-emerald-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(52,211,153,0.15)] group flex flex-col justify-between">
                <div>
                    <div class="text-emerald-400 font-mono text-xs uppercase tracking-widest mb-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                        [ Zona Centrală ]
                    </div>
                    <h3 class="text-white text-xl font-extrabold uppercase tracking-wide mb-3">The Sanctuary<br>Food</h3>
                    <p class="text-zinc-400 text-sm font-light leading-relaxed mb-6">Restaurant și terasă în inima complexului: mâncare gătită, cafea, preparate calibrate nutritiv pentru clienți și sportivi.</p>
                </div>
                <div class="space-y-2">
                    <span class="inline-block text-[11px] font-mono text-emerald-400/80 uppercase tracking-widest border border-emerald-500/20 bg-emerald-950/20 px-2 py-0.5 rounded-sm">
                        Deschis Zilnic • 08:00 - 22:00
                    </span>
                    <div>
                        <a href="{{ url('/food') }}" class="text-emerald-400 text-xs font-bold uppercase tracking-wider inline-flex items-center gap-2 transition-all duration-300 group-hover:underline group-hover:tracking-widest group-hover:gap-3">
                            Descoperă Zona Food &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Wing Right: Fitness -->
            <div class="reveal reveal-d4 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-blue-500 p-8 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-blue-500/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(59,130,246,0.15)] group flex flex-col justify-between">
                <div>
                    <div class="text-blue-500 font-mono text-xs uppercase tracking-widest mb-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                        [ Aripa Dreaptă ]
                    </div>
                    <h3 class="text-white text-xl font-extrabold uppercase tracking-wide mb-3">The Iron Sanctuary<br>Fitness</h3>
                    <p class="text-zinc-400 text-sm font-light leading-relaxed mb-6">Sală de forță și cardio, antrenori pe sală și program de antrenament pentru oricine vrea să se antreneze serios.</p>
                </div>
                <a href="{{ url('/fitness') }}" class="text-blue-500 text-xs font-bold uppercase tracking-wider inline-flex items-center gap-2 transition-all duration-300 group-hover:underline group-hover:tracking-widest group-hover:gap-3">
                    Intră în Club &rarr;
                </a>
            </div>

        </div>
    </section>
@endsection

@extends('layouts.sanctuary')

@section('title', 'The Sanctuary Food | Restaurant & Lounge')

@section('orbs')
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden h-[140vh]">
        <div class="orb absolute -top-16 left-1/2 -translate-x-1/2 h-80 w-80 rounded-full bg-emerald-500/18 blur-3xl"></div>
        <div class="orb orb-delay absolute top-[40%] left-[10%] h-72 w-72 rounded-full bg-emerald-400/10 blur-3xl"></div>
    </div>
@endsection

@section('content')
    <!-- Hero / Title -->
    <section class="relative z-10 max-w-4xl mx-auto px-6 pt-12 pb-6 text-center">
        <p class="reveal text-emerald-400/80 text-xs uppercase tracking-[0.25em] mb-3 font-mono">[ Zona Centrală ] // The Sanctuary Food</p>
        <h1 class="reveal reveal-d1 text-4xl md:text-6xl font-black tracking-wide uppercase text-white mb-4">
            Zona <span class="relative inline-block">
                <span class="title-live pointer-events-none absolute inset-0 text-emerald-400 blur-xl opacity-50" aria-hidden="true">Food</span>
                <span class="relative bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent">Food</span>
            </span>
        </h1>
        <p class="reveal reveal-d2 text-zinc-400 text-sm md:text-base font-light leading-relaxed max-w-2xl mx-auto">
            Restaurantul și bistro-ul din inima complexului: alimentație nutritivă calibrată pentru sportivi, preparate proaspete gătite pe loc, specialty coffee și lounge de relaxare în timp ce mașina ta este în service sau după un antrenament intens.
        </p>
    </section>

    <!-- Services Grid: Pure Tailwind Utilities -->
    <section class="relative z-10 max-w-7xl mx-auto px-6 mt-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <article class="reveal reveal-d2 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-emerald-400 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-emerald-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(52,211,153,0.15)]">
                <div class="text-emerald-400 font-mono text-xs uppercase tracking-widest mb-3">01</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Nutriție Sportivă</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Macro-uri calculate la miligram: preparate high-protein, carbohidrați lenți și grăsimi sănătoase, optimizate pentru masă musculară sau definire.</p>
            </article>

            <article class="reveal reveal-d2 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-emerald-400 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-emerald-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(52,211,153,0.15)]">
                <div class="text-emerald-400 font-mono text-xs uppercase tracking-widest mb-3">02</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Specialty Coffee</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Espresso de origine unică, flat white artizanal, băuturi tonice și apă alcalină cu electroliți pentru energie și hidratare optimă.</p>
            </article>

            <article class="reveal reveal-d3 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-emerald-400 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-emerald-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(52,211,153,0.15)]">
                <div class="text-emerald-400 font-mono text-xs uppercase tracking-widest mb-3">03</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Shake & Recovery Bar</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Bar dedicat pentru proteine din zer sau izolat, creatină, aminoacizi BCAA/EAA și smoothie-uri presate la rece pline de vitamine.</p>
            </article>

            <article class="reveal reveal-d3 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-emerald-400 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-emerald-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(52,211,153,0.15)]">
                <div class="text-emerald-400 font-mono text-xs uppercase tracking-widest mb-3">04</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Bucătărie Deschisă</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Preparate calde gătite pe loc din carne proaspătă, pește, orez basmati, legume la grătar și sosuri curate, fără procesare industrială.</p>
            </article>

            <article class="reveal reveal-d4 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-emerald-400 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-emerald-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(52,211,153,0.15)]">
                <div class="text-emerald-400 font-mono text-xs uppercase tracking-widest mb-3">05</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Terasă & Lounge</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Spațiu confortabil de relaxare și lucru cu Wi-Fi de mare viteză. Locul perfect unde să iei prânzul cât timp mașina ta e pe elevator.</p>
            </article>

            <article class="reveal reveal-d4 bg-[#0b0b0e]/70 border border-zinc-900 border-l-4 border-l-emerald-400 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-emerald-400/50 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),0_0_20px_rgba(52,211,153,0.15)]">
                <div class="text-emerald-400 font-mono text-xs uppercase tracking-widest mb-3">06</div>
                <h2 class="text-white text-lg font-extrabold uppercase tracking-wide mb-2">Meal Prep To-Go</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed">Pachete nutritive ambalate ermetic pentru întreaga zi sau abonament săptămânal. Gata de luat la pachet la finalul antrenamentului.</p>
            </article>
        </div>
    </section>

    <!-- Process Steps: Pure Tailwind Utilities -->
    <section class="relative z-10 max-w-7xl mx-auto px-6 mt-8 grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="reveal bg-[#0b0b0e]/70 border border-zinc-900 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:border-zinc-800">
            <div class="text-zinc-500 font-mono text-xs uppercase tracking-widest mb-2">Pasul 1</div>
            <h3 class="text-white font-extrabold uppercase tracking-wide mb-2">Alegi meniul</h3>
            <p class="text-zinc-400 text-sm font-light leading-relaxed">Comanzi la bar sau alegi meniul sportiv configurat pe obiectivele tale: încărcare calorică, refacere sau o gustare lejeră.</p>
        </div>

        <div class="reveal reveal-d2 bg-[#0b0b0e]/70 border border-zinc-900 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:border-zinc-800">
            <div class="text-zinc-500 font-mono text-xs uppercase tracking-widest mb-2">Pasul 2</div>
            <h3 class="text-white font-extrabold uppercase tracking-wide mb-2">Pregătit pe loc</h3>
            <p class="text-zinc-400 text-sm font-light leading-relaxed">Bucătarii pregătesc ingredientele proaspăt, respectând cu strictețe cantitățile, gramajul de macronutrienți și calitatea fiecărei porții.</p>
        </div>

        <div class="reveal reveal-d3 bg-[#0b0b0e]/70 border border-zinc-900 p-7 rounded-sm backdrop-blur-xl transition-all duration-300 hover:border-zinc-800">
            <div class="text-zinc-500 font-mono text-xs uppercase tracking-widest mb-2">Pasul 3</div>
            <h3 class="text-white font-extrabold uppercase tracking-wide mb-2">Savurezi sau iei la pachet</h3>
            <p class="text-zinc-400 text-sm font-light leading-relaxed">Te bucuri de masă în atmosfera complexului cu vedere la terasă sau iei pachetul ambalat ermetic direct la drum.</p>
        </div>
    </section>

    <!-- Action CTA: Pure Tailwind Utilities -->
    <section class="relative z-10 max-w-7xl mx-auto px-6 mt-8">
        <div class="reveal border border-emerald-400/20 bg-emerald-950/10 p-8 md:p-10 rounded-sm flex flex-col md:flex-row md:items-center md:justify-between gap-6 backdrop-blur-xl">
            <div>
                <h2 class="text-white text-xl font-extrabold uppercase tracking-wide mb-2">Vrei o masă sau comandă to-go?</h2>
                <p class="text-zinc-400 text-sm font-light leading-relaxed max-w-xl">Treci pe la restaurantul din zona centrală a complexului sau solicită barului pachetul tău personalizat de meal-prep. Te așteptăm zilnic între 08:00 și 22:00.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0">
                <a href="{{ route('bookings.create', ['wing' => 'food']) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-emerald-400 text-black text-xs font-black uppercase tracking-widest hover:bg-emerald-300 transition-colors shadow-[0_0_15px_rgba(52,211,153,0.3)]">
                    Rezervă Masă & Food &rarr;
                </a>
                <a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 border border-emerald-400/40 text-emerald-400 text-xs font-bold uppercase tracking-widest hover:bg-emerald-400/10 transition-colors">
                    ← Înapoi
                </a>
            </div>
        </div>
    </section>
@endsection

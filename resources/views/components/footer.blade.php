<footer class="relative z-10 border-t border-zinc-900 bg-[#060608]/90 text-zinc-400 mt-20 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand Column -->
            <div class="md:col-span-1 space-y-3">
                <a href="{{ url('/') }}" class="text-xl font-black uppercase tracking-wider bg-gradient-to-r from-cyan-400 to-emerald-400 bg-clip-text text-transparent inline-flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-sm bg-cyan-400 rotate-45"></span>
                    The Sanctuary
                </a>
                <p class="text-xs text-zinc-500 font-mono leading-relaxed">
                    Ecosistem hibrid unificat: atelier mecanic avansat, restaurant & lounge, și club de forță & condiționare fizică.
                </p>
                <div class="pt-2 text-[11px] font-mono text-zinc-600">
                    EST. 2026 // TOATE DREPTURILE REZERVATE
                </div>
            </div>

            <!-- Wing 1: Auto -->
            <div class="space-y-3">
                <div class="text-xs font-mono uppercase tracking-widest text-cyan-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-cyan-400 rounded-full"></span>
                    Aripa Stângă
                </div>
                <h4 class="text-white text-sm font-bold uppercase tracking-wide">The Iron Sanctuary Auto</h4>
                <ul class="text-xs space-y-2 text-zinc-400">
                    <li><a href="{{ url('/garaj') }}" class="hover:text-cyan-400 transition-colors">Diagnoză Computerizată</a></li>
                    <li><a href="{{ url('/garaj') }}" class="hover:text-cyan-400 transition-colors">Revizii & Mentenanță</a></li>
                    <li><a href="{{ url('/garaj') }}" class="hover:text-cyan-400 transition-colors">Mecanică & Elevatoare</a></li>
                    <li><a href="{{ url('/garaj') }}" class="hover:text-cyan-400 transition-colors">Service Moto Dedicat</a></li>
                </ul>
            </div>

            <!-- Wing 2: Food -->
            <div class="space-y-3">
                <div class="text-xs font-mono uppercase tracking-widest text-emerald-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                    Zona Centrală
                </div>
                <h4 class="text-white text-sm font-bold uppercase tracking-wide">The Sanctuary Food</h4>
                <ul class="text-xs space-y-2 text-zinc-400">
                    <li><a href="{{ url('/food') }}" class="hover:text-emerald-400 transition-colors">Meniu Nutriție & Fitness</a></li>
                    <li><a href="{{ url('/food') }}" class="hover:text-emerald-400 transition-colors">Specialty Coffee & Bar</a></li>
                    <li><a href="{{ url('/food') }}" class="hover:text-emerald-400 transition-colors">Terasă & Zonă Așteptare</a></li>
                    <li><a href="{{ url('/food') }}" class="hover:text-emerald-400 transition-colors">Rezervări Evenimente</a></li>
                </ul>
            </div>

            <!-- Wing 3: Fitness -->
            <div class="space-y-3">
                <div class="text-xs font-mono uppercase tracking-widest text-blue-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span>
                    Aripa Dreaptă
                </div>
                <h4 class="text-white text-sm font-bold uppercase tracking-wide">The Iron Sanctuary Fitness</h4>
                <ul class="text-xs space-y-2 text-zinc-400">
                    <li><a href="{{ url('/fitness') }}" class="hover:text-blue-400 transition-colors">Zonă Forță & Free Weights</a></li>
                    <li><a href="{{ url('/fitness') }}" class="hover:text-blue-400 transition-colors">Zonă Cardio de Înaltă Intensitate</a></li>
                    <li><a href="{{ url('/fitness') }}" class="hover:text-blue-400 transition-colors">Antrenori Specializați</a></li>
                    <li><a href="{{ url('/fitness') }}" class="hover:text-blue-400 transition-colors">Abonamente & Pachete</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom bar -->
        <div class="mt-12 pt-6 border-t border-zinc-900 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-mono text-zinc-600">
            <div class="flex items-center gap-4">
                <span>COORDONATE: COMPLEX THE SANCTUARY</span>
                <span class="hidden sm:inline">•</span>
                <span>STATUS: ARHITECTURĂ UNIFICATĂ</span>
            </div>
            <div class="flex items-center gap-2 text-zinc-500">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="text-zinc-400">SISTEM INTEGRAT ACTIV</span>
            </div>
        </div>
    </div>
</footer>

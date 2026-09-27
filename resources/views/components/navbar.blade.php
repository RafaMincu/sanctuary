@php
    $isHome = request()->is('/');
    $isGaraj = request()->is('garaj*');
    $isFood = request()->is('food*');
    $isFitness = request()->is('fitness*');
@endphp

<header class="sticky top-0 z-50 border-b border-zinc-900 bg-[#060608]/85 backdrop-blur-xl">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 py-4 flex justify-between items-center gap-4">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="text-xl sm:text-2xl font-black tracking-wider uppercase bg-gradient-to-r from-cyan-400 to-emerald-400 bg-clip-text text-transparent hover:opacity-90 transition-opacity flex items-center gap-2">
            <span class="inline-block w-2.5 h-2.5 rounded-sm bg-cyan-400 rotate-45"></span>
            The Sanctuary
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-6 text-xs uppercase tracking-widest font-bold">
            <a href="{{ url('/') }}" 
               class="transition-colors duration-200 py-1 border-b-2 {{ $isHome ? 'text-white border-emerald-400' : 'text-zinc-500 border-transparent hover:text-zinc-200' }}">
                Acasă
            </a>
            <a href="{{ url('/garaj') }}" 
               class="transition-colors duration-200 py-1 border-b-2 {{ $isGaraj ? 'text-cyan-400 border-cyan-400' : 'text-zinc-500 border-transparent hover:text-cyan-400/80' }}">
                Garaj Auto
            </a>
            <a href="{{ url('/food') }}" 
               class="transition-colors duration-200 py-1 border-b-2 {{ $isFood ? 'text-emerald-400 border-emerald-400' : 'text-zinc-500 border-transparent hover:text-emerald-400' }}">
                Zona Food
            </a>
            <a href="{{ url('/fitness') }}" 
               class="transition-colors duration-200 py-1 border-b-2 {{ $isFitness ? 'text-blue-400 border-blue-400' : 'text-zinc-500 border-transparent hover:text-blue-400/80' }}">
                Sală Fitness
            </a>
        </nav>

        <!-- Right Side: Status Badge + Mobile Toggle -->
        <div class="flex items-center gap-3">
            <div class="status-live hidden sm:flex px-3 py-1.5 border border-emerald-500/30 bg-emerald-950/20 text-emerald-400 text-[11px] uppercase tracking-widest font-bold rounded-sm items-center gap-2">
                <span class="hud-dot inline-block h-1.5 w-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                <span>Sistem Online</span>
            </div>

            <!-- Mobile Hamburger Button -->
            <button type="button" 
                    id="mobile-nav-toggle" 
                    aria-label="Deschide meniul de navigare"
                    aria-expanded="false"
                    class="md:hidden p-2 rounded-sm border border-zinc-800 bg-[#0b0b0e] text-zinc-400 hover:text-white hover:border-zinc-700 focus:outline-none transition-colors">
                <svg id="menu-icon-bars" class="w-5 h-5 block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="menu-icon-close" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div id="mobile-nav-menu" class="hidden md:hidden border-t border-zinc-900 bg-[#060608]/95 px-6 py-4 space-y-3">
        <a href="{{ url('/') }}" 
           class="block py-2 text-xs uppercase tracking-widest font-bold {{ $isHome ? 'text-white pl-2 border-l-2 border-emerald-400' : 'text-zinc-400 hover:text-white' }}">
            Acasă
        </a>
        <a href="{{ url('/garaj') }}" 
           class="block py-2 text-xs uppercase tracking-widest font-bold {{ $isGaraj ? 'text-cyan-400 pl-2 border-l-2 border-cyan-400' : 'text-zinc-400 hover:text-cyan-400' }}">
            Garaj Auto (Aripa Stângă)
        </a>
        <a href="{{ url('/food') }}" 
           class="block py-2 text-xs uppercase tracking-widest font-bold {{ $isFood ? 'text-emerald-400 pl-2 border-l-2 border-emerald-400' : 'text-zinc-400 hover:text-emerald-400' }}">
            Zona Food (Zona Centrală)
        </a>
        <a href="{{ url('/fitness') }}" 
           class="block py-2 text-xs uppercase tracking-widest font-bold {{ $isFitness ? 'text-blue-400 pl-2 border-l-2 border-blue-400' : 'text-zinc-400 hover:text-blue-400' }}">
            Sală Fitness (Aripa Dreaptă)
        </a>
        
        <div class="pt-3 border-t border-zinc-900/80 flex items-center justify-between text-[11px] font-mono text-zinc-500">
            <span>STATUS ECOSISTEM:</span>
            <span class="text-emerald-400 font-bold flex items-center gap-1.5">
                <span class="hud-dot inline-block h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                ONLINE
            </span>
        </div>
    </div>
</header>

<script>
    (function() {
        var toggle = document.getElementById('mobile-nav-toggle');
        var menu = document.getElementById('mobile-nav-menu');
        var iconBars = document.getElementById('menu-icon-bars');
        var iconClose = document.getElementById('menu-icon-close');

        if (toggle && menu) {
            toggle.addEventListener('click', function() {
                var isOpen = !menu.classList.contains('hidden');
                if (isOpen) {
                    menu.classList.add('hidden');
                    iconBars.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                } else {
                    menu.classList.remove('hidden');
                    iconBars.classList.add('hidden');
                    iconClose.classList.remove('hidden');
                    toggle.setAttribute('aria-expanded', 'true');
                }
            });
        }
    })();
</script>

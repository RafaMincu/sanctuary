<!DOCTYPE html>
<html lang="ro" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'THE SANCTUARY | Ultimate Lifestyle Ecosystem')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes grid-drift {
            from { background-position: 0 0, 0 0; }
            to { background-position: 5rem 5rem, 5rem 5rem; }
        }
        @keyframes scan-down {
            0% { transform: translateY(-20%); opacity: 0; }
            12% { opacity: 0.55; }
            88% { opacity: 0.55; }
            100% { transform: translateY(120%); opacity: 0; }
        }
        @keyframes glow-pulse {
            0%, 100% { opacity: 0.35; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.08); }
        }
        @keyframes status-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.35); }
            50% { box-shadow: 0 0 12px 2px rgba(52, 211, 153, 0.25); }
        }
        @keyframes title-glow {
            0%, 100% { text-shadow: 0 0 18px rgba(34, 211, 238, 0.45); }
            50% { text-shadow: 0 0 32px rgba(52, 211, 153, 0.65); }
        }
        @keyframes frame-glow {
            0%, 100% { box-shadow: 0 20px 50px rgba(0,0,0,0.9), 0 0 15px rgba(34,211,238,0.08); }
            50% { box-shadow: 0 20px 50px rgba(0,0,0,0.9), 0 0 28px rgba(52,211,153,0.18); }
        }
        @keyframes image-scan {
            0% { transform: translateX(-120%) skewX(-12deg); }
            100% { transform: translateX(220%) skewX(-12deg); }
        }
        @keyframes hud-blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.35; }
        }
        .reveal { animation: fade-up 0.85s ease-out both; }
        .reveal-d1 { animation-delay: 0.08s; }
        .reveal-d2 { animation-delay: 0.2s; }
        .reveal-d3 { animation-delay: 0.32s; }
        .reveal-d4 { animation-delay: 0.44s; }
        .grid-bg {
            background-image:
                linear-gradient(to right, #111827 1px, transparent 1px),
                linear-gradient(to bottom, #111827 1px, transparent 1px);
            background-size: 5rem 5rem;
            animation: grid-drift 28s linear infinite;
        }
        .scanline { animation: scan-down 7.5s ease-in-out infinite; }
        .orb { animation: glow-pulse 8s ease-in-out infinite; }
        .orb-delay { animation-delay: -3s; }
        .status-live { animation: status-pulse 2.4s ease-in-out infinite; }
        .title-live { animation: title-glow 4.5s ease-in-out infinite; }
        .frame-live { animation: frame-glow 5s ease-in-out infinite; }
        .img-scan { animation: image-scan 5.5s ease-in-out infinite; }
        .hud-dot { animation: hud-blink 1.6s step-end infinite; }
        @media (prefers-reduced-motion: reduce) {
            .reveal, .grid-bg, .scanline, .orb, .status-live, .title-live, .frame-live, .img-scan, .hud-dot {
                animation: none !important;
            }
        }
    </style>
</head>
<body class="bg-[#060608] text-zinc-100 font-sans antialiased overflow-x-hidden min-h-screen flex flex-col justify-between">

    <div class="absolute inset-0 grid-bg opacity-15 pointer-events-none z-0 h-screen"></div>
    <div class="absolute inset-0 pointer-events-none z-[1] h-screen overflow-hidden">
        <div class="scanline h-32 w-full bg-gradient-to-b from-transparent via-cyan-400/10 to-transparent"></div>
    </div>
    
    @yield('orbs')

    <div id="cursor-glow" class="pointer-events-none fixed inset-0 z-[2] opacity-0 transition-opacity duration-300" style="background: radial-gradient(420px circle at 50% 30%, rgba(34,211,238,0.09), transparent 55%);"></div>

    <x-navbar />

    <main class="relative z-10 flex-grow">
        @yield('content')
    </main>

    <x-footer />

    <script>
        (function () {
            var glow = document.getElementById('cursor-glow');
            if (!glow || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            var visible = false;
            document.addEventListener('mousemove', function (e) {
                if (!visible) {
                    glow.style.opacity = '1';
                    visible = true;
                }
                glow.style.background = 'radial-gradient(420px circle at ' + e.clientX + 'px ' + e.clientY + 'px, rgba(34,211,238,0.1), transparent 55%)';
            });
            document.addEventListener('mouseleave', function () {
                glow.style.opacity = '0';
                visible = false;
            });
        })();
    </script>

</body>
</html>

<!DOCTYPE html>
<html lang="ro" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'THE SANCTUARY | Ultimate Lifestyle Ecosystem')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- Vite este folosit aici doar pentru Turbo Drive. Stilurile site-ului sunt
         generate de Tailwind CDN; încărcarea simultană a Tailwind v4 din Vite
         suprascrie culorile/gradientele butoanelor existente. --}}
    @vite('resources/js/app.js')

    {{-- Chat real-time (Laravel Reverb) — pusher-js + laravel-echo via CDN.
         Dacă cheile REVERB lipsesc sau WS nu pornește, widgeturile rămân
         funcționale prin polling (fallback la 15s). --}}
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@2.6.1/dist/echo.iife.js"></script>
    <script>
        (function () {
            var cfg = {
                key: @json(config('broadcasting.connections.reverb.key')),
                host: @json(config('broadcasting.connections.reverb.options.host')),
                port: @json(config('broadcasting.connections.reverb.options.port')),
                scheme: @json(config('broadcasting.connections.reverb.options.scheme'))
            };

            if (!cfg.key || typeof Pusher === 'undefined' || typeof Echo === 'undefined') {
                return; // fără configurație → doar polling
            }

            try {
                window.Echo = new Echo({
                    broadcaster: 'reverb',
                    key: cfg.key,
                    wsHost: cfg.host || window.location.hostname,
                    wsPort: cfg.port || 8080,
                    wssPort: cfg.port || 443,
                    forceTLS: cfg.scheme === 'https',
                    enabledTransports: ['ws', 'wss']
                });
            } catch (e) {
                window.Echo = null; // WS indisponibil → polling
            }
        })();
    </script>
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

    @php
        $audioFiles = collect(['mp3', 'ogg', 'wav', 'm4a'])
            ->flatMap(fn ($extension) => glob(public_path("audio/*.{$extension}")) ?: [])
            ->sortBy(fn ($file) => strcasecmp(basename($file), 'The Sanctuary.mp3') === 0 ? '0' : '1_' . basename($file))
            ->values();

        $musicPlaylist = $audioFiles->map(function ($file) {
            $filename = basename($file);

            return [
                'title' => str($filename)->beforeLast('.')->replace(['-', '_'], ' ')->title()->toString(),
                'src' => asset('audio/' . $filename),
            ];
        })->values();
    @endphp

    @if ($musicPlaylist->isNotEmpty())
        <section id="music-player" data-turbo-permanent class="fixed bottom-4 right-4 z-40 w-[calc(100%-2rem)] max-w-sm overflow-hidden rounded-2xl border border-cyan-300/20 bg-[#0b0f17]/90 p-3 shadow-[0_18px_60px_rgba(0,0,0,0.55),0_0_36px_rgba(34,211,238,0.12)] backdrop-blur-2xl transition-all duration-300 sm:bottom-6 sm:right-6" aria-label="Player muzical">
            <audio id="background-music" preload="metadata"></audio>

            <div class="mb-3 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="flex items-center gap-1.5 text-[10px] font-mono font-bold uppercase tracking-[0.18em] text-cyan-400">
                        <span id="music-status-dot" class="h-1.5 w-1.5 rounded-full bg-zinc-600"></span>
                        Ambient audio
                    </p>
                    <p id="music-track-title" class="truncate text-sm font-bold text-zinc-100"></p>
                </div>
                <div class="flex shrink-0 items-center gap-2">
                    <span id="music-time" class="font-mono text-[11px] text-zinc-500">0:00 / 0:00</span>
                    <button id="music-minimize" type="button" class="flex h-7 w-7 items-center justify-center rounded-full border border-zinc-700/80 bg-zinc-900/70 text-zinc-400 transition-all hover:border-cyan-400 hover:text-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-400/70" aria-label="Minimizează playerul" title="Minimizează playerul">⌄</button>
                </div>
            </div>

            <input id="music-progress" class="mb-3 block h-1.5 w-full cursor-pointer accent-cyan-400" type="range" min="0" max="0" value="0" step="0.1" aria-label="Progres melodie">

            <div class="flex items-center gap-2">
                <button id="music-previous" type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-zinc-700 bg-zinc-900/50 text-zinc-300 transition-all hover:border-cyan-400 hover:bg-cyan-400/10 hover:text-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-400/70" aria-label="Melodia anterioară" title="Melodia anterioară">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 5v14m12-14-9 7 9 7V5Z"/></svg>
                </button>
                <button id="music-play" type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-cyan-400/60 bg-cyan-400/10 text-cyan-300 transition-all hover:border-cyan-300 hover:bg-cyan-400/20 hover:text-cyan-100 focus:outline-none focus:ring-2 focus:ring-cyan-300" aria-label="Redă muzica" title="Redă muzica">
                    <span id="music-play-icon"><svg class="h-4 w-4 translate-x-px" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5.5v13l10-6.5-10-6.5Z"/></svg></span>
                </button>
                <button id="music-next" type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-zinc-700 bg-zinc-900/50 text-zinc-300 transition-all hover:border-cyan-400 hover:bg-cyan-400/10 hover:text-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-400/70" aria-label="Melodia următoare" title="Melodia următoare">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 5v14M6 5l9 7-9 7V5Z"/></svg>
                </button>

                <label class="sr-only" for="music-track-select">Alege melodia</label>
                <select id="music-track-select" class="min-w-0 flex-1 border border-zinc-700 bg-zinc-950 px-2 py-2 text-xs text-zinc-300 outline-none transition-colors focus:border-cyan-400">
                    @foreach ($musicPlaylist as $index => $track)
                        <option value="{{ $index }}">{{ $track['title'] }}</option>
                    @endforeach
                </select>
            </div>
        </section>

        <div id="music-bubble" data-turbo-permanent class="fixed bottom-24 right-4 z-40 hidden sm:bottom-28 sm:right-6" aria-label="Comenzi rapide muzică">
            <div class="relative">
                <button id="music-bubble-toggle" type="button" class="group relative flex h-14 w-14 items-center justify-center rounded-full border border-cyan-300/40 bg-[#0b0f17]/95 text-xl text-cyan-200 shadow-[0_12px_32px_rgba(0,0,0,0.55),0_0_24px_rgba(34,211,238,0.24)] backdrop-blur-xl transition-all duration-300 hover:scale-105 hover:border-emerald-300 hover:text-emerald-200 focus:outline-none focus:ring-2 focus:ring-cyan-300" aria-label="Redă muzica" title="Redă muzica">
                    <span id="music-bubble-icon"><svg class="h-6 w-6 translate-x-px" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5.5v13l10-6.5-10-6.5Z"/></svg></span>
                    <span id="music-bubble-pulse" class="pointer-events-none absolute inset-1 rounded-full border border-emerald-300/0"></span>
                </button>
                <button id="music-expand" type="button" class="absolute -right-1 -top-1 flex h-6 w-6 items-center justify-center rounded-full border border-cyan-300/50 bg-zinc-950 text-[11px] text-cyan-200 shadow-lg transition-all hover:scale-110 hover:border-emerald-300 hover:text-emerald-200 focus:outline-none focus:ring-2 focus:ring-cyan-400/70" aria-label="Deschide playerul" title="Deschide playerul">⌃</button>
            </div>
        </div>

        <script>
            (function () {
                if (window.__sanctuaryMusicPlayerInitialized) {
                    return;
                }

                window.__sanctuaryMusicPlayerInitialized = true;

                var playlist = @json($musicPlaylist);
                var storagePrefix = 'sanctuary_music_';
                var audio = document.getElementById('background-music');
                var playButton = document.getElementById('music-play');
                var playIcon = document.getElementById('music-play-icon');
                var minimizeButton = document.getElementById('music-minimize');
                var musicPlayer = document.getElementById('music-player');
                var musicBubble = document.getElementById('music-bubble');
                var bubbleToggle = document.getElementById('music-bubble-toggle');
                var bubbleIcon = document.getElementById('music-bubble-icon');
                var bubblePulse = document.getElementById('music-bubble-pulse');
                var expandButton = document.getElementById('music-expand');
                var previousButton = document.getElementById('music-previous');
                var nextButton = document.getElementById('music-next');
                var progress = document.getElementById('music-progress');
                var trackTitle = document.getElementById('music-track-title');
                var trackSelect = document.getElementById('music-track-select');
                var timeLabel = document.getElementById('music-time');
                var statusDot = document.getElementById('music-status-dot');
                var currentTrack = 0;
                var restoreTime = 0;
                var shouldResume = false;
                var isMinimized = false;
                var isFirstVisit = false;
                var autoStartOnFirstInteraction = false;
                var isAttemptingPlayback = false;

                function getStored(key, fallback) {
                    try {
                        var value = localStorage.getItem(storagePrefix + key);
                        return value === null ? fallback : value;
                    } catch (error) {
                        return fallback;
                    }
                }

                function setStored(key, value) {
                    try {
                        localStorage.setItem(storagePrefix + key, String(value));
                    } catch (error) {
                        // Playerul rămâne funcțional și când stocarea locală este blocată.
                    }
                }

                function formatTime(seconds) {
                    seconds = Number.isFinite(seconds) ? Math.floor(seconds) : 0;
                    return Math.floor(seconds / 60) + ':' + String(seconds % 60).padStart(2, '0');
                }

                function updateTime() {
                    progress.max = Number.isFinite(audio.duration) ? audio.duration : 0;
                    progress.value = audio.currentTime || 0;
                    timeLabel.textContent = formatTime(audio.currentTime) + ' / ' + formatTime(audio.duration);
                }

                function updatePlayerState() {
                    var isPlaying = !audio.paused;
                    playIcon.innerHTML = isPlaying
                        ? '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 5h3v14H7V5Zm7 0h3v14h-3V5Z"/></svg>'
                        : '<svg class="h-4 w-4 translate-x-px" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5.5v13l10-6.5-10-6.5Z"/></svg>';
                    playButton.setAttribute('aria-label', isPlaying ? 'Pune muzica pe pauză' : 'Redă muzica');
                    playButton.title = isPlaying ? 'Pauză' : 'Redă muzica';
                    statusDot.className = 'h-1.5 w-1.5 rounded-full ' + (isPlaying ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.9)]' : 'bg-zinc-600');
                    bubbleIcon.innerHTML = isPlaying
                        ? '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 5h3v14H7V5Zm7 0h3v14h-3V5Z"/></svg>'
                        : '<svg class="h-6 w-6 translate-x-px" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5.5v13l10-6.5-10-6.5Z"/></svg>';
                    bubbleToggle.setAttribute('aria-label', isPlaying ? 'Pune muzica pe pauză' : 'Redă muzica');
                    bubbleToggle.title = isPlaying ? 'Pauză' : 'Redă muzica';
                    bubblePulse.className = 'pointer-events-none absolute inset-1 rounded-full border ' + (isPlaying ? 'border-emerald-300/60 animate-ping' : 'border-emerald-300/0');
                }

                function setMinimized(minimized) {
                    isMinimized = minimized;
                    musicPlayer.classList.toggle('hidden', minimized);
                    musicBubble.classList.toggle('hidden', !minimized);
                    musicBubble.classList.toggle('block', minimized);
                    setStored('minimized', minimized);
                }

                function savePlaybackState() {
                    setStored('track', currentTrack);
                    setStored('time', audio.currentTime || 0);
                    setStored('was_playing', !audio.paused);
                }

                function loadTrack(index, startTime) {
                    currentTrack = (index + playlist.length) % playlist.length;
                    var track = playlist[currentTrack];

                    audio.src = track.src;
                    audio.load();
                    trackTitle.textContent = track.title;
                    trackSelect.value = currentTrack;
                    restoreTime = Math.max(0, Number(startTime) || 0);
                    updateTime();
                    savePlaybackState();
                }

                function playMusic() {
                    if (isAttemptingPlayback) {
                        return;
                    }

                    isAttemptingPlayback = true;
                    audio.play().then(function () {
                        shouldResume = false;
                        autoStartOnFirstInteraction = false;
                        removeGestureListeners();
                        updatePlayerState();
                        savePlaybackState();
                    }).catch(function () {
                        // Browserul cere o interacțiune a utilizatorului înainte de redare.
                        shouldResume = true;
                        updatePlayerState();
                    }).finally(function () {
                        isAttemptingPlayback = false;
                    });
                }

                function changeTrack(offset) {
                    var wasPlaying = !audio.paused;
                    loadTrack(currentTrack + offset, 0);
                    if (wasPlaying) {
                        playMusic();
                    }
                }

                isFirstVisit = getStored('initialized', null) !== 'true';
                currentTrack = isFirstVisit
                    ? playlist.findIndex(function (track) { return /\/The%20Sanctuary\.mp3(?:\?|$)/i.test(track.src); })
                    : Math.min(Math.max(parseInt(getStored('track', '0'), 10) || 0, 0), playlist.length - 1);
                currentTrack = currentTrack >= 0 ? currentTrack : 0;
                restoreTime = isFirstVisit ? 0 : (parseFloat(getStored('time', '0')) || 0);
                shouldResume = !isFirstVisit && getStored('was_playing', 'false') === 'true';
                autoStartOnFirstInteraction = isFirstVisit;
                isMinimized = isFirstVisit || getStored('minimized', 'false') === 'true';
                setStored('initialized', true);
                loadTrack(currentTrack, restoreTime);

                audio.addEventListener('loadedmetadata', function () {
                    if (restoreTime > 0 && restoreTime < audio.duration) {
                        audio.currentTime = restoreTime;
                    }
                    restoreTime = 0;
                    updateTime();
                });

                audio.addEventListener('timeupdate', function () {
                    updateTime();
                    savePlaybackState();
                });

                audio.addEventListener('play', updatePlayerState);
                audio.addEventListener('pause', function () {
                    updatePlayerState();
                    savePlaybackState();
                });
                audio.addEventListener('ended', function () {
                    loadTrack(currentTrack + 1, 0);
                    playMusic();
                });

                playButton.addEventListener('click', function () {
                    if (audio.paused) {
                        playMusic();
                    } else {
                        audio.pause();
                    }
                });

                minimizeButton.addEventListener('click', function () {
                    setMinimized(true);
                });

                expandButton.addEventListener('click', function () {
                    setMinimized(false);
                });

                bubbleToggle.addEventListener('click', function () {
                    if (audio.paused) {
                        playMusic();
                        return;
                    }

                    shouldResume = false;
                    audio.pause();
                    savePlaybackState();
                });

                previousButton.addEventListener('click', function () {
                    changeTrack(-1);
                });

                nextButton.addEventListener('click', function () {
                    changeTrack(1);
                });

                trackSelect.addEventListener('change', function () {
                    var wasPlaying = !audio.paused;
                    loadTrack(parseInt(trackSelect.value, 10) || 0, 0);
                    if (wasPlaying) {
                        playMusic();
                    }
                });

                progress.addEventListener('input', function () {
                    if (Number.isFinite(audio.duration)) {
                        audio.currentTime = Number(progress.value);
                        updateTime();
                    }
                });

                window.addEventListener('pagehide', savePlaybackState);
                document.addEventListener('visibilitychange', function () {
                    if (document.visibilityState === 'hidden') {
                        savePlaybackState();
                    }
                });

                function resumeAfterInteraction() {
                    if ((shouldResume || autoStartOnFirstInteraction) && audio.paused) {
                        playMusic();
                    }
                }

                function addGestureListeners() {
                    document.addEventListener('pointerdown', resumeAfterInteraction, true);
                    document.addEventListener('keydown', resumeAfterInteraction, true);
                }

                function removeGestureListeners() {
                    document.removeEventListener('pointerdown', resumeAfterInteraction, true);
                    document.removeEventListener('keydown', resumeAfterInteraction, true);
                }

                // pointerdown rulează înainte de click și înainte ca Turbo să înceapă
                // navigarea, păstrând "user activation" necesară pentru audio.
                addGestureListeners();

                if (shouldResume) {
                    playMusic();
                }

                setMinimized(isMinimized);
                updatePlayerState();
            })();
        </script>
    @endif

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

    {{-- Live Chat Widget – vizibil doar pentru vizitatori (ascuns pe /admin* ca să nu acopere panoul) --}}
    @unless (request()->is('*admin*'))
        @include('chat.widget')
    @endunless

</body>
</html>

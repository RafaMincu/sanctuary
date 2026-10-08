@extends('layouts.sanctuary')

@section('title', 'Programări & Rezervări | The Sanctuary')

@section('orbs')
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden h-[150vh]">
        <div id="orb-cyan" class="orb absolute -top-16 left-[10%] h-80 w-80 rounded-full bg-cyan-500/15 blur-3xl transition-opacity duration-500"></div>
        <div id="orb-emerald" class="orb orb-delay absolute top-[30%] right-[15%] h-80 w-80 rounded-full bg-emerald-500/15 blur-3xl transition-opacity duration-500"></div>
        <div id="orb-blue" class="orb absolute top-[60%] left-1/2 -translate-x-1/2 h-80 w-80 rounded-full bg-blue-500/12 blur-3xl transition-opacity duration-500"></div>
    </div>
@endsection

@section('content')
    <section class="relative z-10 max-w-4xl mx-auto px-6 pt-12 pb-16">
        <!-- Header -->
        <div class="text-center mb-10">
            <p class="reveal text-zinc-500 text-xs uppercase tracking-[0.25em] mb-2 font-mono">// Terminal Rezervări // Ecosistem Unificat</p>
            <h1 class="reveal reveal-d1 text-3xl md:text-5xl font-black tracking-wide uppercase text-white mb-3">
                Programare <span id="wing-title-accent" class="relative inline-block bg-gradient-to-r from-cyan-400 to-emerald-400 bg-clip-text text-transparent">
                    Sanctuary
                </span>
            </h1>
            <p class="reveal reveal-d2 text-zinc-400 text-sm md:text-base font-light max-w-xl mx-auto">
                Rezervă-ți locul în atelierul auto, la restaurant & lounge sau cu un antrenor dedicat în sala de fitness. Confirmare rapidă și număr unic de tracking.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-8 p-4 rounded-sm border border-rose-500/30 bg-rose-950/20 text-rose-300 text-xs backdrop-blur-md">
                <div class="font-bold uppercase tracking-wider mb-1 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                    Eroare la trimiterea formularului:
                </div>
                <ul class="list-disc list-inside space-y-1 text-zinc-300">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="reveal reveal-d3 bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm backdrop-blur-2xl p-6 sm:p-10 shadow-2xl relative overflow-hidden">
            <div id="wing-top-border" class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-500 to-emerald-400 transition-all duration-300"></div>

            <form action="{{ route('bookings.store') }}" method="POST" id="booking-form" class="space-y-8">
                @csrf

                <!-- Wing Selector -->
                <div>
                    <label class="block text-xs uppercase tracking-widest font-mono text-zinc-400 mb-3">
                        1. Selectează Aripa / Departamentul <span class="text-rose-400">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <!-- Auto Option -->
                        <label class="cursor-pointer">
                            <input type="radio" name="wing" value="auto" class="peer sr-only" {{ old('wing', $selectedWing) === 'auto' ? 'checked' : '' }} onchange="switchWing('auto')">
                            <div class="p-4 rounded-sm border border-zinc-800 bg-zinc-950/50 peer-checked:border-cyan-400 peer-checked:bg-cyan-950/20 hover:border-zinc-700 transition-all text-center">
                                <div class="text-[11px] font-mono text-cyan-400 uppercase tracking-widest mb-1">[ Aripa Stângă ]</div>
                                <div class="text-sm font-bold uppercase tracking-wider text-white">Garaj Auto</div>
                                <div class="text-[11px] text-zinc-500 mt-1">Service & Diagnoză</div>
                            </div>
                        </label>

                        <!-- Food Option -->
                        <label class="cursor-pointer">
                            <input type="radio" name="wing" value="food" class="peer sr-only" {{ old('wing', $selectedWing) === 'food' ? 'checked' : '' }} onchange="switchWing('food')">
                            <div class="p-4 rounded-sm border border-zinc-800 bg-zinc-950/50 peer-checked:border-emerald-400 peer-checked:bg-emerald-950/20 hover:border-zinc-700 transition-all text-center">
                                <div class="text-[11px] font-mono text-emerald-400 uppercase tracking-widest mb-1">[ Zona Centrală ]</div>
                                <div class="text-sm font-bold uppercase tracking-wider text-white">Zona Food</div>
                                <div class="text-[11px] text-zinc-500 mt-1">Restaurant & Lounge</div>
                            </div>
                        </label>

                        <!-- Fitness Option -->
                        <label class="cursor-pointer">
                            <input type="radio" name="wing" value="fitness" class="peer sr-only" {{ old('wing', $selectedWing) === 'fitness' ? 'checked' : '' }} onchange="switchWing('fitness')">
                            <div class="p-4 rounded-sm border border-zinc-800 bg-zinc-950/50 peer-checked:border-blue-500 peer-checked:bg-blue-950/20 hover:border-zinc-700 transition-all text-center">
                                <div class="text-[11px] font-mono text-blue-400 uppercase tracking-widest mb-1">[ Aripa Dreaptă ]</div>
                                <div class="text-sm font-bold uppercase tracking-wider text-white">Sală Fitness</div>
                                <div class="text-[11px] text-zinc-500 mt-1">Antrenor & Forță</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Service Selector -->
                <div>
                    <label for="service_type" class="block text-xs uppercase tracking-widest font-mono text-zinc-400 mb-2">
                        2. Serviciu Solicitat <span class="text-rose-400">*</span>
                    </label>
                    <select id="service_type" name="service_type" required class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-sm px-4 py-3 rounded-sm focus:outline-none focus:border-cyan-400 transition-colors">
                        <!-- Options populated via JS depending on wing -->
                    </select>
                </div>

                <!-- Wing-Specific Dynamic Fields -->
                <!-- Auto Fields -->
                <div id="wing-fields-auto" class="space-y-4 p-4 border border-cyan-400/20 bg-cyan-950/10 rounded-sm">
                    <div class="text-xs font-mono uppercase tracking-wider text-cyan-400">// Detalii Vehicul (Aripa Stângă)</div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 uppercase mb-1">Marcă Vehicul</label>
                            <input type="text" name="metadata[vehicle_make]" value="{{ old('metadata.vehicle_make') }}" placeholder="Ex: BMW, Audi, Porsche" class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-xs px-3 py-2.5 rounded-sm focus:outline-none focus:border-cyan-400">
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 uppercase mb-1">Model & An</label>
                            <input type="text" name="metadata[vehicle_model]" value="{{ old('metadata.vehicle_model') }}" placeholder="Ex: M3 Competition (2022)" class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-xs px-3 py-2.5 rounded-sm focus:outline-none focus:border-cyan-400">
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 uppercase mb-1">Număr Înmatriculare</label>
                            <input type="text" name="metadata[vehicle_plate]" value="{{ old('metadata.vehicle_plate') }}" placeholder="Ex: B-999-SNC (opțional)" class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-xs px-3 py-2.5 rounded-sm focus:outline-none focus:border-cyan-400 uppercase">
                        </div>
                    </div>
                </div>

                <!-- Food Fields -->
                <div id="wing-fields-food" class="space-y-4 p-4 border border-emerald-400/20 bg-emerald-950/10 rounded-sm hidden">
                    <div class="text-xs font-mono uppercase tracking-wider text-emerald-400">// Detalii Rezervare Masă / Lounge (Zona Centrală)</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 uppercase mb-1">Număr de Persoane</label>
                            <select name="metadata[guests_count]" class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-xs px-3 py-2.5 rounded-sm focus:outline-none focus:border-emerald-400">
                                <option value="1">1 Persoană</option>
                                <option value="2" selected>2 Persoane</option>
                                <option value="3-4">3 - 4 Persoane</option>
                                <option value="5-8">5 - 8 Persoane</option>
                                <option value="8+">Grup mare (8+ Persoane)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 uppercase mb-1">Zonă Preferată</label>
                            <select name="metadata[seating_area]" class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-xs px-3 py-2.5 rounded-sm focus:outline-none focus:border-emerald-400">
                                <option value="restaurant">Restaurant Central & Bucătărie Deschisă</option>
                                <option value="lounge">Lounge VIP & Specialty Coffee</option>
                                <option value="terasa">Terasă exterioară</option>
                                <option value="to_go">Pachet To-Go / Fără masă rezervată</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Fitness Fields -->
                <div id="wing-fields-fitness" class="space-y-4 p-4 border border-blue-500/20 bg-blue-950/10 rounded-sm hidden">
                    <div class="text-xs font-mono uppercase tracking-wider text-blue-400">// Detalii Antrenament & Club (Aripa Dreaptă)</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 uppercase mb-1">Obiectiv Principal</label>
                            <select name="metadata[fitness_goal]" class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-xs px-3 py-2.5 rounded-sm focus:outline-none focus:border-blue-400">
                                <option value="masa">Hipertrofie & Masă Musculară</option>
                                <option value="forta">Creștere Forță & Performanță Atletică</option>
                                <option value="slăbire">Definire corporală & Slăbire</option>
                                <option value="postura">Condiționare fizică generală & Postură</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 uppercase mb-1">Nivel Experiență</label>
                            <select name="metadata[experience_level]" class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-xs px-3 py-2.5 rounded-sm focus:outline-none focus:border-blue-400">
                                <option value="incepator">Începător (Sub 6 luni)</option>
                                <option value="intermediar" selected>Intermediar (1 - 3 ani de sală)</option>
                                <option value="avansat">Avansat / Atlet de performanță</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Client Contact Info -->
                <div>
                    <label class="block text-xs uppercase tracking-widest font-mono text-zinc-400 mb-3">
                        3. Informații de Contact & Dată Programare <span class="text-rose-400">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="client_name" class="block text-[11px] font-mono text-zinc-400 uppercase mb-1">Nume & Prenume <span class="text-rose-400">*</span></label>
                            <input type="text" id="client_name" name="client_name" required value="{{ old('client_name') }}" placeholder="Alex Popescu" class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-sm px-4 py-3 rounded-sm focus:outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label for="client_phone" class="block text-[11px] font-mono text-zinc-400 uppercase mb-1">Număr de Telefon <span class="text-rose-400">*</span></label>
                            <input type="tel" id="client_phone" name="client_phone" required value="{{ old('client_phone') }}" placeholder="07XXXXXXXX" class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-sm px-4 py-3 rounded-sm focus:outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label for="client_email" class="block text-[11px] font-mono text-zinc-400 uppercase mb-1">Adresă de Email <span class="text-rose-400">*</span></label>
                            <input type="email" id="client_email" name="client_email" required value="{{ old('client_email') }}" placeholder="alex@exemplu.ro" class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-sm px-4 py-3 rounded-sm focus:outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label for="scheduled_at" class="block text-[11px] font-mono text-zinc-400 uppercase mb-1">Data & Ora Dorită <span class="text-rose-400">*</span></label>
                            <input type="datetime-local" id="scheduled_at" name="scheduled_at" required value="{{ old('scheduled_at', now()->addDay()->setHour(10)->setMinute(0)->format('Y-m-d\TH:i')) }}" class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-sm px-4 py-3 rounded-sm focus:outline-none focus:border-cyan-400">
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-[11px] font-mono text-zinc-400 uppercase mb-1">Note Suplimentare / Detalii despre solicitare (opțional)</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Mențiuni particulare, simptome mașină, preferințe alimentare sau întrebări pentru echipă..." class="w-full bg-[#060608] border border-zinc-800 text-zinc-200 text-sm p-4 rounded-sm focus:outline-none focus:border-cyan-400">{{ old('notes') }}</textarea>
                </div>

                <!-- Submit Button & Navigation -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-zinc-900">
                    <a href="{{ route('bookings.lookup') }}" class="text-xs font-mono uppercase tracking-wider text-zinc-500 hover:text-zinc-300 transition-colors">
                        Ai deja o programare? Verifică statusul &rarr;
                    </a>
                    
                    <button type="submit" id="submit-btn" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-cyan-400 to-emerald-400 text-black text-xs font-black uppercase tracking-widest hover:opacity-95 transition-opacity shadow-[0_0_20px_rgba(34,211,238,0.25)] flex items-center justify-center gap-2">
                        <span>Transmite Programarea</span>
                        <span>&rarr;</span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Interactive Script -->
    <script>
        const servicesByWing = @json($servicesByWing);
        const oldService = @json(old('service_type'));

        function switchWing(wing) {
            const autoFields = document.getElementById('wing-fields-auto');
            const foodFields = document.getElementById('wing-fields-food');
            const fitnessFields = document.getElementById('wing-fields-fitness');
            const selectEl = document.getElementById('service_type');
            const topBorder = document.getElementById('wing-top-border');
            const accentText = document.getElementById('wing-title-accent');

            // Hide all specific fields
            autoFields.classList.add('hidden');
            foodFields.classList.add('hidden');
            fitnessFields.classList.add('hidden');

            // Populate services
            selectEl.innerHTML = '';
            const services = servicesByWing[wing] || {};
            for (const [key, label] of Object.entries(services)) {
                const opt = document.createElement('option');
                opt.value = key;
                opt.textContent = label;
                if (oldService === key) {
                    opt.selected = true;
                }
                selectEl.appendChild(opt);
            }

            // Adjust visuals based on wing
            if (wing === 'auto') {
                autoFields.classList.remove('hidden');
                topBorder.className = 'absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-500 to-cyan-300 transition-all duration-300';
                accentText.className = 'relative inline-block bg-gradient-to-r from-cyan-400 to-cyan-200 bg-clip-text text-transparent';
                accentText.textContent = 'Garaj Auto';
            } else if (wing === 'food') {
                foodFields.classList.remove('hidden');
                topBorder.className = 'absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-300 transition-all duration-300';
                accentText.className = 'relative inline-block bg-gradient-to-r from-emerald-400 to-teal-200 bg-clip-text text-transparent';
                accentText.textContent = 'Zona Food';
            } else if (wing === 'fitness') {
                fitnessFields.classList.remove('hidden');
                topBorder.className = 'absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-cyan-400 transition-all duration-300';
                accentText.className = 'relative inline-block bg-gradient-to-r from-blue-400 to-cyan-300 bg-clip-text text-transparent';
                accentText.textContent = 'Sală Fitness';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const checkedRadio = document.querySelector('input[name="wing"]:checked');
            if (checkedRadio) {
                switchWing(checkedRadio.value);
            } else {
                switchWing('auto');
            }
        });
    </script>
@endsection

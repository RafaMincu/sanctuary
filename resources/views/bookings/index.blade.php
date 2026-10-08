@extends('layouts.sanctuary')

@section('title', 'Gestiune Programări & Solicitări | The Sanctuary')

@section('content')
    <section class="relative z-10 max-w-7xl mx-auto px-6 pt-10 pb-20">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <p class="text-zinc-500 text-xs uppercase tracking-[0.25em] font-mono mb-1">// Panou Control // Personal & Recepție</p>
                <h1 class="text-2xl sm:text-4xl font-black uppercase text-white tracking-wide">
                    Gestiune <span class="bg-gradient-to-r from-cyan-400 to-emerald-400 bg-clip-text text-transparent">Programări</span>
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('bookings.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-cyan-400 to-emerald-400 text-black text-xs font-bold uppercase tracking-wider hover:opacity-95 transition-opacity">
                    + Adaugă Programare
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-sm border border-emerald-500/40 bg-emerald-950/20 text-emerald-300 text-xs backdrop-blur-md flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Stats Bar -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3 mb-8">
            <div class="p-3 bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm">
                <div class="text-[10px] font-mono text-zinc-500 uppercase">Total Cereri</div>
                <div class="text-xl font-bold font-mono text-white mt-1">{{ $stats['total'] }}</div>
            </div>
            <div class="p-3 bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm">
                <div class="text-[10px] font-mono text-yellow-400 uppercase">În Așteptare</div>
                <div class="text-xl font-bold font-mono text-yellow-400 mt-1">{{ $stats['pending'] }}</div>
            </div>
            <div class="p-3 bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm">
                <div class="text-[10px] font-mono text-emerald-400 uppercase">Confirmate</div>
                <div class="text-xl font-bold font-mono text-emerald-400 mt-1">{{ $stats['confirmed'] }}</div>
            </div>
            <div class="p-3 bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm">
                <div class="text-[10px] font-mono text-cyan-400 uppercase">Finalizate</div>
                <div class="text-xl font-bold font-mono text-cyan-400 mt-1">{{ $stats['completed'] }}</div>
            </div>
            <div class="p-3 bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm">
                <div class="text-[10px] font-mono text-cyan-400 uppercase">Aripa Auto</div>
                <div class="text-xl font-bold font-mono text-zinc-200 mt-1">{{ $stats['auto'] }}</div>
            </div>
            <div class="p-3 bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm">
                <div class="text-[10px] font-mono text-emerald-400 uppercase">Zona Food</div>
                <div class="text-xl font-bold font-mono text-zinc-200 mt-1">{{ $stats['food'] }}</div>
            </div>
            <div class="p-3 bg-[#0b0b0e]/80 border border-zinc-900 rounded-sm">
                <div class="text-[10px] font-mono text-blue-400 uppercase">Sală Fitness</div>
                <div class="text-xl font-bold font-mono text-zinc-200 mt-1">{{ $stats['fitness'] }}</div>
            </div>
        </div>

        <!-- Filters Form -->
        <div class="bg-[#0b0b0e]/80 border border-zinc-900 p-4 rounded-sm backdrop-blur-xl mb-6">
            <form action="{{ route('bookings.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <!-- Wing Filter -->
                <div>
                    <label class="block text-[10px] font-mono text-zinc-500 uppercase mb-1">Aripă / Departament</label>
                    <select name="wing" onchange="this.form.submit()" class="w-full bg-[#060608] border border-zinc-800 text-zinc-300 text-xs px-3 py-2 rounded-sm focus:outline-none focus:border-cyan-400">
                        <option value="">Toate Aripiile</option>
                        <option value="auto" {{ $currentWing === 'auto' ? 'selected' : '' }}>Garaj Auto</option>
                        <option value="food" {{ $currentWing === 'food' ? 'selected' : '' }}>Zona Food</option>
                        <option value="fitness" {{ $currentWing === 'fitness' ? 'selected' : '' }}>Sală Fitness</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-[10px] font-mono text-zinc-500 uppercase mb-1">Status</label>
                    <select name="status" onchange="this.form.submit()" class="w-full bg-[#060608] border border-zinc-800 text-zinc-300 text-xs px-3 py-2 rounded-sm focus:outline-none focus:border-cyan-400">
                        <option value="">Toate Statusurile</option>
                        <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>În Așteptare</option>
                        <option value="confirmed" {{ $currentStatus === 'confirmed' ? 'selected' : '' }}>Confirmată</option>
                        <option value="completed" {{ $currentStatus === 'completed' ? 'selected' : '' }}>Finalizată</option>
                        <option value="cancelled" {{ $currentStatus === 'cancelled' ? 'selected' : '' }}>Anulată</option>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="sm:col-span-2 flex items-end gap-2">
                    <div class="flex-grow">
                        <label class="block text-[10px] font-mono text-zinc-500 uppercase mb-1">Căutare (Cod, Nume, Telefon)</label>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Ex: SNC-..., Popescu, 07..." class="w-full bg-[#060608] border border-zinc-800 text-zinc-300 text-xs px-3 py-2 rounded-sm focus:outline-none focus:border-cyan-400">
                    </div>
                    <button type="submit" class="px-4 py-2 border border-zinc-800 text-zinc-300 hover:text-white hover:border-zinc-700 text-xs font-mono uppercase rounded-sm">
                        Filtrează
                    </button>
                    @if ($currentWing || $currentStatus || $search)
                        <a href="{{ route('bookings.index') }}" class="px-3 py-2 text-rose-400 hover:text-rose-300 text-xs font-mono uppercase">
                            Resetează
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="bg-[#0b0b0e]/90 border border-zinc-900 rounded-sm overflow-hidden backdrop-blur-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-mono">
                    <thead class="border-b border-zinc-900 bg-zinc-950/70 text-zinc-500 uppercase text-[10px]">
                        <tr>
                            <th class="p-3.5">Cod</th>
                            <th class="p-3.5">Departament</th>
                            <th class="p-3.5">Client & Contact</th>
                            <th class="p-3.5">Programat Pentru</th>
                            <th class="p-3.5">Serviciu & Detalii</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5 text-right">Acțiuni Gestiune</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-900/80">
                        @forelse ($bookings as $booking)
                            <tr class="hover:bg-zinc-900/30 transition-colors">
                                <td class="p-3.5 font-bold text-{{ $booking->wing_color }}-400">
                                    <a href="{{ route('bookings.show', $booking->booking_number) }}" class="hover:underline">
                                        {{ $booking->booking_number }}
                                    </a>
                                </td>
                                <td class="p-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] bg-zinc-900 border border-zinc-800 text-zinc-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $booking->wing_color }}-400"></span>
                                        {{ ucfirst($booking->wing) }}
                                    </span>
                                </td>
                                <td class="p-3.5">
                                    <div class="text-zinc-200 font-bold font-sans">{{ $booking->client_name }}</div>
                                    <div class="text-zinc-500 text-[11px]">{{ $booking->client_phone }}</div>
                                    <div class="text-zinc-600 text-[10px]">{{ $booking->client_email }}</div>
                                </td>
                                <td class="p-3.5">
                                    <div class="text-zinc-200 font-bold">{{ $booking->scheduled_at->format('d.m.Y') }}</div>
                                    <div class="text-zinc-500">{{ $booking->scheduled_at->format('H:i') }}</div>
                                </td>
                                <td class="p-3.5 max-w-xs">
                                    <div class="text-zinc-300 uppercase font-bold">{{ $booking->service_type }}</div>
                                    @if (!empty($booking->metadata))
                                        <div class="text-[10px] text-zinc-500 truncate mt-0.5">
                                            @foreach ($booking->metadata as $k => $v)
                                                @if(!empty($v)) {{ $k }}: {{ $v }} | @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="p-3.5">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] uppercase font-bold bg-zinc-950 border border-zinc-800 text-{{ $booking->status_color }}-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $booking->status_color }}-400"></span>
                                        {{ $booking->status_label }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-right space-x-1">
                                    <div class="inline-flex items-center gap-1">
                                        @if ($booking->status !== 'confirmed')
                                            <form action="{{ route('bookings.status', $booking->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" title="Confirmă" class="px-2 py-1 text-[10px] uppercase font-bold bg-emerald-950/40 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-900/50 rounded-sm">
                                                    Confirmă
                                                </button>
                                            </form>
                                        @endif

                                        @if ($booking->status !== 'completed')
                                            <form action="{{ route('bookings.status', $booking->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" title="Finalizează" class="px-2 py-1 text-[10px] uppercase font-bold bg-cyan-950/40 border border-cyan-500/30 text-cyan-400 hover:bg-cyan-900/50 rounded-sm">
                                                    Finalizează
                                                </button>
                                            </form>
                                        @endif

                                        @if ($booking->status !== 'cancelled')
                                            <form action="{{ route('bookings.status', $booking->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" title="Anulează" class="px-2 py-1 text-[10px] uppercase font-bold bg-rose-950/40 border border-rose-500/30 text-rose-400 hover:bg-rose-900/50 rounded-sm">
                                                    Anulează
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-zinc-500">
                                    Nu a fost găsită nicio programare conform filtrelor selectate.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($bookings->hasPages())
                <div class="p-4 border-t border-zinc-900">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection

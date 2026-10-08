<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use Illuminate\Http\Request;

        // FORȚĂM MIGRAREA DIRECT AICI (Adaugă exact acest bloc înainte de linia 106)
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        } catch (\Exception $e) {
            // Dacă tabelele există deja, Postgres va da o eroare pe care o ignorăm în siguranță aici
        }

        // Codul tău neschimbat care dădea eroare:
        if ($phone !== '') {
            $query->where('client_phone', 'like', '%' . $phone . '%');
        }

        $booking = $query->latest()->first();


class BookingController extends Controller
{
    /**
     * Services map by wing
     */
    protected array $servicesByWing = [
        'auto' => [
            'diagnoza' => 'Diagnoză computerizată & mecanică',
            'revizie' => 'Revizie periodică completă',
            'frane' => 'Sistem de frânare & siguranță',
            'suspensie' => 'Suspensie, direcție & tren de rulare',
            'motor' => 'Mecanică avansată & reparații motor',
            'consultanta' => 'Verificare achiziție & consultanță',
        ],
        'food' => [
            'masa_restaurant' => 'Rezervare masă restaurant (2-8 pers)',
            'lounge_vip' => 'Masă în Lounge VIP & Specialty Coffee',
            'terasa' => 'Rezervare terasă în aer liber',
            'meal_prep' => 'Comandă pachet nutriție Meal-Prep to-go',
            'eveniment' => 'Rezervare grup / eveniment privat',
        ],
        'fitness' => [
            'evaluare' => 'Sesiune evaluare fizică & compoziție (Gratuit)',
            'antrenor_personal' => 'Antrenament 1-la-1 cu antrenor dedicat',
            'inscriere_club' => 'Înscriere club & acces facilități',
            'plan_nutritiv' => 'Consultanță program antrenament & dietă',
        ],
    ];

    /**
     * Show booking form with optional wing preselection
     */
    public function create(Request $request)
    {
        $selectedWing = $request->query('wing', 'auto');
        if (!in_array($selectedWing, ['auto', 'food', 'fitness'])) {
            $selectedWing = 'auto';
        }

        return view('bookings.create', [
            'selectedWing' => $selectedWing,
            'servicesByWing' => $this->servicesByWing,
        ]);
    }

    /**
     * Store new booking
     */
    public function store(StoreBookingRequest $request)
    {
        $bookingNumber = Booking::generateBookingNumber();

        $booking = Booking::create([
            'booking_number' => $bookingNumber,
            'wing' => $request->input('wing'),
            'service_type' => $request->input('service_type'),
            'client_name' => $request->input('client_name'),
            'client_email' => $request->input('client_email'),
            'client_phone' => $request->input('client_phone'),
            'scheduled_at' => $request->input('scheduled_at'),
            'notes' => $request->input('notes'),
            'metadata' => $request->input('metadata', []),
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.show', $booking->booking_number)
            ->with('success', 'Programarea ta la The Sanctuary a fost înregistrată cu succes! Te așteptăm.');
    }

    /**
     * Show public booking confirmation & status details
     */
    public function show(string $booking_number)
    {
        $booking = Booking::where('booking_number', strtoupper(trim($booking_number)))->firstOrFail();

        return view('bookings.show', [
            'booking' => $booking,
        ]);
    }

    /**
     * Public lookup form and results
     */
    public function lookup(Request $request)
    {
        $code = strtoupper(trim((string) $request->input('code', '')));
        $phone = trim((string) $request->input('phone', ''));
        $booking = null;
        $searched = false;

        if ($code !== '' || $phone !== '') {
            $searched = true;
            $query = Booking::query();

            if ($code !== '') {
                $query->where('booking_number', $code);
            }
            if ($phone !== '') {
                $query->where('client_phone', 'like', '%' . $phone . '%');
            }

            $booking = $query->latest()->first();
        }

        return view('bookings.lookup', [
            'booking' => $booking,
            'code' => $code,
            'phone' => $phone,
            'searched' => $searched,
        ]);
    }

    /**
     * Admin / Staff Overview Dashboard
     */
    public function index(Request $request)
    {
        $wing = $request->query('wing');
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Booking::query()->latest();

        if ($wing && in_array($wing, ['auto', 'food', 'fitness'])) {
            $query->where('wing', $wing);
        }

        if ($status && in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('client_phone', 'like', "%{$search}%")
                  ->orWhere('client_email', 'like', "%{$search}%");
            });
        }

        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'auto' => Booking::where('wing', 'auto')->count(),
            'food' => Booking::where('wing', 'food')->count(),
            'fitness' => Booking::where('wing', 'fitness')->count(),
        ];

        $bookings = $query->paginate(15)->withQueryString();

        return view('bookings.index', [
            'bookings' => $bookings,
            'stats' => $stats,
            'currentWing' => $wing,
            'currentStatus' => $status,
            'search' => $search,
        ]);
    }

    /**
     * Update booking status (confirmed, completed, cancelled, pending)
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $booking->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', "Statusul programării {$booking->booking_number} a fost actualizat la '{$booking->status_label}'.");
    }
}

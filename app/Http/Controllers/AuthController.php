<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('account.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [], [
            'email' => 'adresa de email',
            'password' => 'parola',
        ]);

        if (!Auth::attempt([
            'email' => strtolower($credentials['email']),
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Adresa de email sau parola sunt incorecte.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        $this->claimGuestBookings(Auth::user());

        return redirect()->intended(route('account.dashboard'))
            ->with('success', 'Bine ai revenit la The Sanctuary.');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('account.dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [], [
            'name' => 'numele',
            'email' => 'adresa de email',
            'password' => 'parola',
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'email' => strtolower($validated['email']),
            'password' => $validated['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $this->claimGuestBookings($user);

        return redirect()->route('account.dashboard')
            ->with('success', 'Contul tău a fost creat. Poți gestiona programările din acest panou.');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $this->claimGuestBookings($user);

        $bookings = Booking::where('user_id', $user->id)
            ->latest('scheduled_at')
            ->get();

        return view('account.dashboard', compact('bookings'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Te-ai deconectat în siguranță.');
    }

    /**
     * Atașează contului programările făcute anterior ca vizitator, exclusiv când
     * emailul coincide și programarea nu aparține deja unui alt utilizator.
     */
    private function claimGuestBookings(User $user): void
    {
        Booking::query()
            ->whereNull('user_id')
            ->whereRaw('LOWER(client_email) = ?', [strtolower(trim($user->email))])
            ->update(['user_id' => $user->id]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ClaimsGuestBookings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleAuthController extends Controller
{
    use ClaimsGuestBookings;

    /**
     * Redirecționează utilizatorul către ecranul de consent Google.
     * Păstrează pagina de unde a venit pentru redirect-ul de după login.
     */
    public function redirect(Request $request)
    {
        $referer = $request->headers->get('referer');
        $guestPages = [route('login'), route('register'), route('google.redirect')];

        // Păstrăm ca țintă doar pagini reale ale aplicației, nu și paginile
        // de autentificare (altfel redirect()->intended() ar putea reintra
        // în bucla middleware-ului guest).
        if ($referer
            && str_starts_with($referer, url('/'))
            && ! in_array($referer, $guestPages, true)) {
            $request->session()->put('url.intended', $referer);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback-ul OAuth: preia emailul venit de la Google și autentifică
     * utilizatorul — logare directă dacă emailul există deja, cont nou altfel.
     */
    public function callback(Request $request)
    {
        // Google a respins autentificarea (ex. utilizatorul a apăsat "Anulează").
        if ($request->has('error')) {
            return redirect()->route('login')
                ->with('error', 'Autentificarea cu Google a fost anulată. Te poți autentifica cu email și parolă.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();

            // 1. Preia email-ul venit de la Google.
            $email = strtolower(trim((string) $googleUser->getEmail()));

            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return redirect()->route('login')
                    ->with('error', 'Contul Google nu a furnizat o adresă de email validă.');
            }

            // 2. Caută în tabelul 'users' dacă există deja acest email.
            $user = User::where('email', $email)->first();

            // 3. Dacă email-ul EXISTĂ deja → îl logăm DIRECT în sesiune,
            //    ignorând complet parola veche. Dacă NU există → creăm cont nou
            //    (4.) cu o parolă generată aleatoriu.
            if (! $user) {
                $name = trim((string) $googleUser->getName());

                $user = User::create([
                    'name' => $name !== '' ? $name : $email,
                    'email' => $email,
                    'password' => Hash::make(Str::random(16)),
                    'google_id' => (string) $googleUser->getId(),
                ]);

                // Emailul vine de la Google, unde este deja verificat.
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            // Autentificare + regenerarea sesiunii (protecție session fixation).
            Auth::login($user);
            $request->session()->regenerate();
            $this->claimGuestBookings($user);

            // 5. Redirecționare către dashboard.
            return redirect('/dashboard')
                ->with('success', 'Te-ai autentificat cu Google. Bine ai revenit la The Sanctuary.');
        } catch (InvalidStateException) {
            // State CSRF lipsit sau invalid (callback fals / sesiune expirată).
            return redirect()->route('login')
                ->with('error', 'Sesiunea de autentificare Google a expirat. Încearcă din nou.');
        } catch (\Throwable $e) {
            // Erori de rețea, token invalid etc. — aplicația nu trebuie să crape.
            report($e);

            return redirect()->route('login')
                ->with('error', 'Autentificarea cu Google a eșuat. Încearcă din nou.');
        }
    }
}

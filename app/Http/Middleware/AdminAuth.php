<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     * Checks if admin is logged in via session.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // Prezența adminului pentru widget-ul vizitatorului: reînnoită la fiecare
        // cerere autentificată (polling-ul panoului). Dacă adminul închide browserul
        // fără logout, expiră singură prin TTL → „Offline” în live chat.
        Cache::put('chat:admin_online', true, now()->addSeconds(15));

        // Numele adminului activ → widget-ul arată cine răspunde („Ana scrie...”).
        $adminName = $request->session()->get('admin_name');
        if ($adminName) {
            Cache::put('chat:admin_name', $adminName, now()->addSeconds(15));
        }

        return $next($request);
    }
}

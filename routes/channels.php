<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Aici declarăm ce canale private poate abona fiecare client WebSocket.
| Channelul fiecărui vizitator conține session-id-ul lui, deci poate fi
| autorizat doar de proprietarul acelei sesiuni.
|
| NOTĂ: autorizarea canalelor private (inclusiv pentru admin, care e doar în
| sesiune, și pentru vizitatorii anonimi, care nu sunt pe guard-ul Auth) este
| posibilă pentru că înregistrăm un broadcaster personalizat
| (App\Broadcasting\ReverbBroadcaster) prin BroadcastServiceProvider. Acesta
| trece dincolo de verificarea guard-ului și deleagă verificarea reală către
| callback-urile de mai jos, unde comparăm cheia sesiunii / flag-ul de admin.
|
*/

// Widgetul chat al vizitatorului: fiecare sesiune își vede DOAR propriul
// canal. Utilizatorii autentificați folosesc cheia "user_{id}" (stabilă
// chiar și după regenerarea sesiunii), iar oaspeții session-id-ul curent.
Broadcast::channel('visitor.chat.{sessionKey}', function ($user, string $sessionKey) {
    $request = request();

    if (! $request->hasSession()) {
        return false;
    }

    $currentKey = auth()->check()
        ? 'user_' . auth()->id()
        : $request->session()->getId();

    return hash_equals($currentKey, $sessionKey);
});

// Panourile admin (lista, conversația, switcherul) — pe baza sesiunii de
// admin, nu a unui cont User autentificat.
Broadcast::channel('admin.chat', function ($user) {
    return (bool) request()->session()->get('admin_logged_in');
});

<?php

namespace App\Broadcasting;

use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Broadcaster personalizat pentru Reverb.
 *
 * Problema ce o rezolvă: Laravel cere, pentru orice canal private-/presence-,
 * ca request-ul de autentificare WebSocket să aibă deja un user pe guard-ul
 * Auth (PusherBroadcaster::auth() verifică retrieveUser()). În aplicația noastră:
 *   - Adminul e autentificat doar în sesiune (flag admin_logged_in), nu pe guard.
 *   - Vizitatorii sunt anonimi (guest), deci nu au user pe guard.
 * Dacă păstrăm comportamentul implicit, ambele categorii de canale private sunt
 * respinse înainte de a ajunge la callback-ul din routes/channels.php, chiar
 * dacă acolo facem verificarea corectă prin sesiune.
 *
 * Această clasă suprascrie auth() ca să TREACĂ dincolo de verificarea guard-ului
 * atunci când guard-ul e null, dar callback-ul din routes/channels.php (apelat
 * mai jos prin parent::verifyUserCanAccessChannel) se ocupă de verificarea reală:
 *   - visitor.chat.{sessionKey}: hash_equals($cheie_sesiune, $sessionKey)
 *   - admin.chat: admin_logged_in === true
 * Deci securitatea rămâne neschimbată.
 */
class ReverbBroadcaster extends PusherBroadcaster
{
    public function auth($request)
    {
        $channelName = $this->normalizeChannelName($request->channel_name);

        // Dacă există deja user pe guard, Laravel poate face verificarea obișnuită.
        if ($this->retrieveUser($request, $channelName)) {
            return parent::auth($request);
        }

        // Nu există user pe guard (admin pe sesiune sau guest anonim).
        // Sărim peste verificarea guard-ului și mergem direct la callback-ul
        // din routes/channels.php care verifică prin sesiune.
        return parent::verifyUserCanAccessChannel($request, $channelName);
    }
}

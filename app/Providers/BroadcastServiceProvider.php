<?php

namespace App\Providers;

use App\Broadcasting\ReverbBroadcaster;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Înlocuim broadcaster-ul implicit "reverb" cu cel nostru, care permite
        // autorizarea canalelor private și pe sesiune (fără guard Auth).
        Broadcast::extend('reverb', function ($app, array $config) {
            $pusher = new \Pusher\Pusher(
                $config['key'] ?? '',
                $config['secret'] ?? '',
                $config['app_id'] ?? '',
                $config['options'] ?? []
            );

            return new ReverbBroadcaster($pusher);
        });
    }
}

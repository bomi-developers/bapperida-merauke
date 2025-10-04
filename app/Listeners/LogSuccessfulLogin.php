<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\LoginLog;

use IlluminateAuthEventsLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        LoginLog::create([
            'id_user' => $event->user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'logged_in_at' => now(),
        ]);
    }
}
<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\LoginLog;


use IlluminateAuthEventsLogout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogout
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
    public function handle(Logout $event)
    {
        $log = LoginLog::where('id_user', $event->user->id)
            ->whereNull('logged_out_at')
            ->latest()
            ->first();

        if ($log) {
            $log->update([
                'logged_out_at' => now(),
            ]);
        }
    }
}
<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\Logging;
use Illuminate\Support\Facades\Auth;

class LoginListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handleLogin(Login $event)
    {
        Logging::setAction($event->user->name, Logging::ACTION_IN, [
            'ipAddr' => request()->ip(),
            'macAddr' => exec('getmac')
        ]);
    }

    public function handleLogout(Logout $event)
    {
        Logging::setAction($event->user->name, Logging::ACTION_OUT);
    }
}

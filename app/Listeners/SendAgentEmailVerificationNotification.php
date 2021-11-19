<?php

namespace App\Listeners;

use App\Events\AgentRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Log;

class SendAgentEmailVerificationNotification
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\AgentRegistered  $event
     * @return void
     */
    public function handle(AgentRegistered $event)
    {
        if ($event->user instanceof MustVerifyEmail && !$event->user->hasVerifiedEmail()) {
            $event->user->sendEmailVerificationNotification();
        }
    }
}

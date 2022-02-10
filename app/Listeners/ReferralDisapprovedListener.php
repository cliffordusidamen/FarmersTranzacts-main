<?php

namespace App\Listeners;

use App\Events\ReferralDisapprovedEvent;
use App\Models\ReferralRequest;
use App\Models\User;
use App\Notifications\ReferralApprovedNotification;
use App\Notifications\ReferralDisapprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReferralDisapprovedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ReferralDisapprovedEvent  $event
     * @return void
     */
    public function handle(ReferralDisapprovedEvent $event)
    {
        $event->referral->user->notify(new ReferralDisapprovedNotification($event->referral));
    }
}

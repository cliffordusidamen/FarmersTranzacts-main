<?php

namespace App\Listeners;

use App\Events\ReferralApprovedEvent;
use App\Models\User;
use App\Notifications\ReferralApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReferralApprovedListener
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
     * @param  ReferralApprovedEvent  $event
     * @return void
     */
    public function handle(ReferralApprovedEvent $event)
    {
    
        $farmer = User::create([
            'first_name' => $event->referral->first_name,
            'last_name' => $event->referral->last_name,
            'email' => $event->referral->email,
            'phone' => $event->referral->phone,
            'is_farmer' => true,
            'referred_by' => $event->referral->user_id,
            'date_of_birth' => empty($event->referral->age)
                ? null
                : carbon()->subtract("{$event->referral->age} years")->format('Y-m-d'),
        ]);


        $event->referral->farm->user_id = $farmer->id;
        $event->referral->farm->save();

        $event->referral->user->notify(new ReferralApprovedNotification($event->referral));
    }
}

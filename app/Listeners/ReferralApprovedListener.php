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

        $farmer->farm()->create([
            'business_name' => $event->referral->business_name,
            'crops' => $event->referral->crops_grown,
            'animals' => $event->referral->animals_reared,
            'address_line_1' => $event->referral->address_line_1,
            'address_line_2' => $event->referral->address_line_2,
            'city' => $event->referral->city,
            'farm_size' => $event->referral->farm_size,
            'state_id' => $event->referral->state_id,
            'seed_demand' => $event->referral->seed_demand,
            'fertilizer_demand' => $event->referral->fertilizer_demand,
            'agro_chemical_demand' => $event->referral->agro_chemical_demand,
            'animal_feed_demand' => $event->referral->animal_feed_demand,
        ]);

        $event->referral->user->notify(new ReferralApprovedNotification($event->referral));
    }
}

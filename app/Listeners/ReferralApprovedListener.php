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

        $farm = $farmer->farm()->create([
            'business_name' => $event->referral->business_name,
            'address_line_1' => $event->referral->address_line_1,
            'address_line_2' => $event->referral->address_line_2,
            'city' => $event->referral->city,
            'state_id' => $event->referral->state_id,
        ]);

        $event->referral->farm_id = $farm->id;
        $event->referral->save();

        $farm->seasons()->create([
            'year' => $event->referral->season_year,
            'season' => $event->referral->season,
            'farm_size' => $event->referral->farm_size,
            'number_of_workers' => $event->referral->number_of_workers,
            'seed_input' => $event->referral->seed_input,
            'fertilizer_bags' => $event->referral->fertilizer_bags,
            'agro_chemical_input' => $event->referral->agro_chemical_input,
            'animal_feed_input' => $event->referral->animal_feed_input,
            'crops_harvest' => $event->referral->crops_harvest,
            'animals_harvest' => $event->referral->animals_harvest,
            'crops_sale' => $event->referral->crops_sale,
            'animals_sale' => $event->referral->animals_sale,
        ]);

        $event->referral->user->notify(new ReferralApprovedNotification($event->referral));
    }
}

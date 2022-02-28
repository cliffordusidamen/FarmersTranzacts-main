<?php

namespace App\Listeners;

use App\Events\FarmerReferralEvent;
use App\Mail\NewReferralNotifyAdminMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class FarmerReferralListener
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
     * @param  \App\Events\FarmerReferralEvent  $event
     * @return void
     */
    public function handle(FarmerReferralEvent $event)
    {
        $event->referralRequest->user->notify(new \App\Notifications\ReferralRequestReceivedNotification($event->referralRequest));
        
        $mail = new NewReferralNotifyAdminMail($event->referralRequest);
        Mail::to(config('mail.from.address'))->send($mail);
    }
}

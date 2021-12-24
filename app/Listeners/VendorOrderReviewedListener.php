<?php

namespace App\Listeners;

use App\Events\VendorOrderReviewedEvent;
use App\Mail\NewReviewNotifyAdminMail;
use App\Notifications\VendorOrderReviewedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VendorOrderReviewedListener
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
     * @param  \App\Events\VendorOrderReviewedEvent $event
     * @return void
     */
    public function handle(VendorOrderReviewedEvent $event)
    {
        $event->vendorOrder->vendor->users()->first()->notify(new VendorOrderReviewedNotification(
            $event->vendorOrder
        ));

        $mail = new NewReviewNotifyAdminMail($event->vendorOrder);
        Mail::to(config('mail.from.address'))->send($mail);

    }
}

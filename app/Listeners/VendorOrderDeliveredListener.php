<?php

namespace App\Listeners;

use App\Events\VendorOrderDeliveredEvent;
use App\Notifications\VendorOrderDeliveredNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class VendorOrderDeliveredListener
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
     * @param  \App\Events\VendorOrderDeliveredEvent $event
     * @return void
     */
    public function handle(VendorOrderDeliveredEvent $event)
    {
        $event->vendorOrder->user->notify(new VendorOrderDeliveredNotification(
            $event->vendorOrder
        ));

    }
}

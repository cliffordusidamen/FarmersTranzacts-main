<?php

namespace App\Listeners;

use App\Events\VendorOrderTransitEvent;
use App\Notifications\VendorOrderTransitNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class VendorOrderTransitListener
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
     * @param  \App\Events\VendorOrderTransitEvent $event
     * @return void
     */
    public function handle(VendorOrderTransitEvent $event)
    {
        $event->vendorOrder->user->notify(new VendorOrderTransitNotification(
            $event->vendorOrder
        ));

    }
}

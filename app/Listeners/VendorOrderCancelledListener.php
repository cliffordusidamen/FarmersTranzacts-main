<?php

namespace App\Listeners;

use App\Mail\VendorOrderCanceledNotifyAdminMail;
use App\Notifications\VendorOrderCancelledShopperNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class VendorOrderCancelledListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $vendorOrder = $event->vendorOrder;
        if ($vendorOrder->user->email) {
            $vendorOrder->user->notify(new VendorOrderCancelledShopperNotification($vendorOrder));
        }

        $adminOrderMail = new VendorOrderCanceledNotifyAdminMail($vendorOrder);
        Mail::to(config('mail.from.address'))->send($adminOrderMail);
    }
}

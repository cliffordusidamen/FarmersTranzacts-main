<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use App\Mail\NewOrderNotifyAdminMail;
use App\Models\Vendor;
use App\Notifications\OrderReceiptNotification;
use App\Notifications\VendorNewOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderCompletedListener
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
     * @param  \App\Events\OrderCompletedEvent  $event
     * @return void
     */
    public function handle(OrderCompletedEvent $event)
    {
        // Send email to the user
        $event->order->user->notify(new OrderReceiptNotification(
            $event->order
        ));

        // send email to the admin
        $adminOrderMail = new NewOrderNotifyAdminMail($event->order);
        Mail::to(config('mail.from.address'))->send($adminOrderMail);

        // Notify all concerned vendors
        $vendorIds = [];

        $event->order->items->each(function ($orderItem, $k)  use(&$vendorIds) {
            $vendorIds[] = $orderItem->product->vendor_id;
        });

        Log::info($vendorIds);
        Vendor::whereIn('id', $vendorIds)->get()->each(fn ($vendor, $k) => (
            $vendor->users()->first()->notify(
                new VendorNewOrderNotification($vendor, $event->order)
            )
        ));
    }
}

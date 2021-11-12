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
        $event->order->user->notify(new OrderReceiptNotification(
            $event->order
        ));

        $adminOrderMail = new NewOrderNotifyAdminMail($event->order);
        Mail::to(config('mail.from.address'))->send($adminOrderMail);

    }
}

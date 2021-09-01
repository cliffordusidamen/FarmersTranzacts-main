<?php

namespace App\Listeners;

use App\Events\ProductApprovalStatusChangedEvent;
use App\Models\Product;
use App\Notifications\ProductApprovedNotification;
use App\Notifications\ProductDisapprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProductApprovalStatusChangedListener
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
     * @param  ProductApprovalStatusChangedEvent  $event
     * @return void
     */
    public function handle(ProductApprovalStatusChangedEvent $event)
    {
        if ($event->vendor->approval_status == Product::STATUS_APPROVED) {
            $event->user->notify(new ProductApprovedNotification($event->vendor));
            return;
        } else {
            $event->user->notify(new ProductDisapprovedNotification($event->vendor));
        }
    }
}

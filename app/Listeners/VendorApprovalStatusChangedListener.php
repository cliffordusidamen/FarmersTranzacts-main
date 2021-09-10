<?php

namespace App\Listeners;

use App\Events\VendorApprovalStatusChangedEvent;
use App\Models\Vendor;
use App\Notifications\VendorApprovedNotification;
use App\Notifications\VendorDisapprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VendorApprovalStatusChangedListener
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
     * @param  VendorApprovalStatusChangedEvent  $event
     * @return void
     */
    public function handle(VendorApprovalStatusChangedEvent $event)
    {
        if ($event->vendor->approval_status == Vendor::STATUS_APPROVED) {
            $event->vendor->initWallet();
            $event->vendor->initStorage();
            $event->user->notify(new VendorApprovedNotification($event->vendor));
            return;
        } else {
            $event->user->notify(new VendorDisapprovedNotification($event->vendor));
        }
    }
}

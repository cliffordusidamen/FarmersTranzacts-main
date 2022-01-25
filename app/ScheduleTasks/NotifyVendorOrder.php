<?php

namespace App\ScheduleTasks;

use App\Models\AppSetting;
use App\Models\Order;
use App\Models\VendorOrder;
use App\Notifications\VendorNewOrderNotification;
use Illuminate\Support\Facades\Log;

class NotifyVendorOrder
{

    /**
     * Find vendor orders that haven't been cancelled and cancellation period has elapsed
     * Send email to vendors with order details
     */
    public function __invoke()
    {
        $orders = Order::notCancelled()
            ->vendorNotNotified()
            ->cancellationPeriodElapsed()
            ->with(['vendorOrders', 'vendorOrders.vendor'])
            ->get();

        if (!$orders->count()) {
            return;
        }

        foreach($orders as $order) {
            foreach($order->vendorOrders as $vendorOrder) {
                $vendorOrder->vendor->users()->first()->notify(
                    new VendorNewOrderNotification($vendorOrder->vendor, $order)
                );
            }
            $order->vendor_notified_at = now()->format('Y-m-d H:i:s');
            $order->save();
        }
    }
}
<?php

namespace App\Mail;

use App\Models\VendorOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorOrderCanceledNotifyAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * VendorOrder that was completed
     * 
     * @var  \App\Models\VendorOrder
     */
    protected VendorOrder $vendorOrder;

    /**
     * Create a new message instance.
     *
     * @param  VendorOrder  $vendorOrder
     * @return void
     */
    public function __construct(VendorOrder $vendorOrder)
    {
        $this->vendorOrder = $vendorOrder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->vendorOrder->load([
            'vendor',
            'items',
            'items.product',
            'deliveryStatus',
        ]);

        return $this
            ->subject('A seller\'s order has been cancelled!')
            ->view('emails.vendor_order_canceled_notify_admin', [
                'vendorOrder' => $this->vendorOrder,
            ]);
    }
}

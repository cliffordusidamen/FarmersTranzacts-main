<?php

namespace App\Mail;

use App\Models\VendorOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewReviewNotifyAdminMail extends Mailable
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
        return $this
            ->subject('New Order Received')
            ->view('emails.new_review_notify_admin', [
                'vendorOrder' => $this->vendorOrder,
            ]);
    }
}

<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderNotifyAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Order that was completed
     * 
     * @var  \App\Models\Order
     */
    protected Order $order;

    /**
     * Create a new message instance.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
            ->view('emails.new_order_notify_admin', [
                'order' => $this->order,
            ]);
    }
}

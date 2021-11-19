<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Vendor;
use App\Models\VendorOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorNewOrderNotification extends Notification
{
    use Queueable;

    /**
     * Vendor that was completed
     * 
     * @var  \App\Models\Vendor
     */
    public Vendor $vendor;

    /**
     * Order that was completed
     * 
     * @var  \App\Models\Order
     */
    public Order $order;

    /**
     * vendor
     * 
     * @var  \App\Models\VendorOrder
     */
    public VendorOrder $vendorOrders;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Vendor  $vendor
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function __construct(Vendor $vendor, Order $order)
    {
        $this->vendor = $vendor;
        $this->order = $order;
        $this->vendorOrder = $order->vendorOrders()->where('vendor_id', $vendor->id)->first();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New order received!')
            ->view('emails.vendor_new_order_notice', [
                'order' => $this->order,
                'vendor' => $this->vendor,
                'vendorOrder' => $this->vendorOrder,
                'user' => $notifiable,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

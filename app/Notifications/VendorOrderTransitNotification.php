<?php

namespace App\Notifications;

use App\Models\VendorOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorOrderTransitNotification extends Notification
{
    use Queueable;

    /**
     * Order that was completed
     * 
     * @var  \App\Models\VendorOrder
     */
    public VendorOrder $vendorOrder;

    /**
     * Create a new notification instance.
     *
     * @param  VendorOrder  $vendorOrder
     * @return void
     */
    public function __construct(VendorOrder $vendorOrder)
    {
        $this->vendorOrder = $vendorOrder;
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
            ->subject("Your order is on the way.")
            ->view('emails.vendor_order_transit', [
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

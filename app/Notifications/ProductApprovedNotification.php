<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductApprovedNotification extends Notification
{
    use Queueable;

    /**
     * Instance of vendor
     * 
     * @var  \App\Models\Product
     */
    public Product $product;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->vendor = $product;
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
            ->subject('You product has been approved')
            ->greeting("Hello, " . $notifiable->full_name . '!')
            ->line("Your product, '{$this->product->name}', has been approved and is now available for purchase online.")
            ->line("You can login to manage your orders and shipments.")
            ->line('Thank you for using our application!');
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

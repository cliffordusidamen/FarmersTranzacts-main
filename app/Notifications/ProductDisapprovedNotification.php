<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductDisapprovedNotification extends Notification
{
    use Queueable;

    /**
     * Instance of product
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
        $this->product = $product;
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
        $message = (new MailMessage)
            ->subject('You product was not approved')
            ->greeting("Hello, " . $notifiable->full_name . '!')
            ->line("We are sorry to inform you that your product, '{$this->product->name}', has not been approved.");

        if (!empty($this->product->reject_reason)) {
            $message
                ->line('')
                ->line('Reason for disapproval: ' . $this->product->disapproval_reason)
                ->line(
                    'You may login to re-submit, after addressing above stated reason(s).'
                );
        }

        return $message
            ->line('')
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

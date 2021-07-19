<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorDisapprovedNotification extends Notification
{
    use Queueable;

    /**
     * Instance of vendor
     * 
     * @var  \App\Models\Vendor
     */
    public Vendor $vendor;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return void
     */
    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
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
            ->subject('You registration was not approved')
            ->greeting("Hello, " . $notifiable->full_name . '!')
            ->line('We are sorry to inform you that your registration has not been approved.');

        if (!empty($this->vendor->reject_reason)) {
            $message
                ->line('')
                ->line('Reason for disapproval: ' . $this->vendor->reject_reason)
                ->line(
                    'You may login to re-submit your registration, after addressing above stated reason(s).'
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

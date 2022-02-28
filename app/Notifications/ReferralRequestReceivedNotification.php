<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReferralRequestReceivedNotification extends Notification
{
    use Queueable;

    /**
     * Referral request
     * 
     * @var \App\Models\ReferralRequest
     */
    public $referralRequest;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\ReferralRequest  $referralRequest
     * @return void
     */
    public function __construct(
        \App\Models\ReferralRequest $referralRequest
    ) {
        $this->referralRequest = $referralRequest;
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
                    ->subject('Farmer Referral Request Received')
                    ->greeting('Hello, ' . $notifiable->first_name)
                    ->line('Your farmer referral request has been received. We will review it and you will be notified soon.')
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

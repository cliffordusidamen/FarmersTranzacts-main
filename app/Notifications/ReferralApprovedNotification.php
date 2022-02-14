<?php

namespace App\Notifications;

use App\Models\ReferralRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReferralApprovedNotification extends Notification
{
    use Queueable;

    /**
     * Referral request instance
     * 
     * @var \App\Models\ReferralRequest
     */
    public $referral;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ReferralRequest $referral)
    {
        $this->referral = $referral;
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
                    ->subject("Farmer referral approved!")
                    ->line(
                        'Your referral, '
                        . ($this->referral->business_name ?? $this->referral->full_name)
                        . ', has been approved.'
                    )
                    ->line('You can now order items on their behalf and earn commission.')
                    ->action('Start shopping now', url('/shop'))
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

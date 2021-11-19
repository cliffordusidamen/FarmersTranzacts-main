<?php

namespace App\Notifications;

use App\Models\Agent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgentApprovedNotification extends Notification
{
    use Queueable;

    /**
     * Instance of agent
     * 
     * @var  \App\Models\Agent
     */
    public Agent $agent;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Agent  $agent
     * @return void
     */
    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
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
            ->subject('You registration has been approved')
            ->greeting("Hello, " . $notifiable->full_name . '!')
            ->line('Your registration has been approved. You can start purchasing items and getting commission.')
            ->line('Welcome aboard!');
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

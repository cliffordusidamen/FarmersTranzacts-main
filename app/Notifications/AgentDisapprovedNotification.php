<?php

namespace App\Notifications;

use App\Models\Agent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgentDisapprovedNotification extends Notification
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
        $message = (new MailMessage)
            ->subject('You registration was not approved')
            ->greeting("Hello, " . $notifiable->full_name . '!')
            ->line('We are sorry to inform you that your registration has not been approved.');

        if (!empty($this->agent->reject_reason)) {
            $message
                ->line('')
                ->line('Reason for disapproval: ' . $this->agent->reject_reason)
                ->line(
                    'You may login to re-submit your registration, after addressing above stated reason(s).'
                );
        }

        return $message
            ->line('')
            ->line('Best regards!')
            ->line(config('app.name'));
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

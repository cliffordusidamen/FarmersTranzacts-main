<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsNotifyAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Request data
     * 
     * @var  array
     */
    protected array $data;

    /**
     * Create a new message instance.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('New message from you website')
            ->view('emails.contact_us_notify_admin_mail', [
                'data' => $this->data,
            ]);
    }
}

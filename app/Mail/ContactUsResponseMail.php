<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsResponseMail extends Mailable
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
            ->subject('Your message has been received')
            ->view('emails.contact_us_response_mail', [
                'data' => $this->data,
            ]);
    }
}

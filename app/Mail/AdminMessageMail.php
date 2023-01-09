<?php

namespace App\Mail;

use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * User to send the mail to
     * 
     * @var \App\Model\User
     */
    public $user;

    /**
     * Message to send
     * 
     * @var \App\Model\Message
     */
    public $adminMessage;

    /**
     * Create a new message instance.
     *
     * @param \App\Model\User $user
     * @param \App\Model\Message $adminMessage
     * @return void
     */
    public function __construct(
        User $user,
        Message $adminMessage
    ) {
        $this->user = $user;
        $this->adminMessage = $adminMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from(config('mail.from'))
            ->to($this->user->email, $this->user->full_name)
            ->subject($this->adminMessage->subject ?? '[No subject]')
            ->view('emails.admin_message', [
                'user' => $this->user,
                'adminMessage' => $this->adminMessage,
            ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewReferralNotifyAdminMail extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * Referral request
     * 
     * @var \App\Models\ReferralRequest
     */
    public $referralRequest;

    /**
     * Create a new message instance.
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('New Referral Request Received')
            ->view('emails.new_referral_notify_admin', [
                'referralRequest' => $this->referralRequest,
            ]);
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FarmerReferralEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Referral request
     * 
     * @var \App\Models\ReferralRequest
     */
    public $referralRequest;

    /**
     * Create a new event instance.
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
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}

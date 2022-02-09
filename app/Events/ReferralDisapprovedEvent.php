<?php

namespace App\Events;

use App\Models\ReferralRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReferralDisapprovedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Referral request instance
     * 
     * @var \App\Models\ReferralRequest
     */
    public $referral;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ReferralRequest $referral)
    {
        $this->referral = $referral;
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

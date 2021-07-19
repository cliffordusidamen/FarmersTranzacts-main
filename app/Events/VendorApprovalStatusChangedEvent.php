<?php

namespace App\Events;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VendorApprovalStatusChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Instance of vendor
     * 
     * @var  \App\Models\Vendor
     */
    public Vendor $vendor;

    /**
     * Instance of user
     * 
     * @var  \App\Models\User
     */
    public User $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Vendor  $vendor
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(Vendor $vendor, User $user)
    {
        $this->vendor = $vendor;
        $this->user = $user;
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

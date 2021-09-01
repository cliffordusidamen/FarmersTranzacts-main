<?php

namespace App\Events;

use App\Models\User;
use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductApprovalStatusChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Instance of vendor
     * 
     * @var  \App\Models\Product
     */
    public Product $vendor;

    /**
     * Instance of user
     * 
     * @var  \App\Models\User
     */
    public User $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Product  $vendor
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(Product $vendor, User $user)
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

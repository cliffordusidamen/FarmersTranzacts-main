<?php

namespace App\Events;

use App\Models\VendorOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VendorOrderReviewedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * VendorOrder that was completed
     * 
     * @var  \App\Models\VendorOrder
     */
    public VendorOrder $vendorOrder;

    /**
     * Create a new event instance.
     *
     * @param  VendorOrder  $vendorOrder
     * @return void
     */
    public function __construct(VendorOrder $vendorOrder)
    {
        $this->vendorOrder = $vendorOrder;
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

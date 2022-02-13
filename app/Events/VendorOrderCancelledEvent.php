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

class VendorOrderCancelledEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Vendor order instance
     * 
     * @var \App\Models\VendorOrder
     */
    public $vendorOrder;

    /**
     * Create a new event instance.
     * @param  \App\Models\VendorOrder  $vendorOrder
     * @return void
     */
    public function __construct(VendorOrder  $vendorOrder)
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

<?php

namespace App\Events;

use App\Models\User;
use App\Models\Agent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AgentApprovalStatusChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Instance of agent
     * 
     * @var  \App\Models\Agent
     */
    public Agent $agent;

    /**
     * Instance of user
     * 
     * @var  \App\Models\User
     */
    public User $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Agent  $agent
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(Agent $agent, User $user)
    {
        $this->agent = $agent;
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

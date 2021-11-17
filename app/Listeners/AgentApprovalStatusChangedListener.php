<?php

namespace App\Listeners;

use App\Events\AgentApprovalStatusChangedEvent;
use App\Models\Agent;
use App\Notifications\AgentApprovedNotification;
use App\Notifications\AgentDisapprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AgentApprovalStatusChangedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AgentApprovalStatusChangedEvent  $event
     * @return void
     */
    public function handle(AgentApprovalStatusChangedEvent $event)
    {
        if ($event->agent->approval_status == Agent::STATUS_APPROVED) {
            $event->user->notify(new AgentApprovedNotification($event->agent));
            return;
        } else {
            $event->user->notify(new AgentDisapprovedNotification($event->agent));
        }
    }
}

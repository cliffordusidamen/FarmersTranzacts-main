<?php

namespace App\Providers;

use App\Listeners\SendAgentEmailVerificationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AgentRegistered::class => [
            SendAgentEmailVerificationNotification::class,
        ],

        \App\Events\VendorApprovalStatusChangedEvent::class => [
            \App\Listeners\VendorApprovalStatusChangedListener::class,
        ],

        \App\Events\OrderCompletedEvent::class => [
            \App\Listeners\OrderCompletedListener::class,
        ],

        \App\Events\VendorOrderTransitEvent::class => [
            \App\Listeners\VendorOrderTransitListener::class,
        ],

        \App\Events\VendorOrderDeliveredEvent::class => [
            \App\Listeners\VendorOrderDeliveredListener::class,
        ],

        \App\Events\AgentApprovalStatusChangedEvent::class => [
            \App\Listeners\AgentApprovalStatusChangedListener::class,
        ],

        \App\Events\VendorOrderReviewedEvent::class => [
            \App\Listeners\VendorOrderReviewedListener::class,
        ],

        \App\Events\ReferralApprovedEvent::class => [
            \App\Listeners\ReferralApprovedListener::class,
        ],

        \App\Events\ReferralDisapprovedEvent::class => [
            \App\Listeners\ReferralDisapprovedListener::class,
        ],

        \App\Events\VendorOrderCancelledEvent::class => [
            \App\Listeners\VendorOrderCancelledListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

<?php

namespace App\Providers;

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
            SendEmailVerificationNotification::class,
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

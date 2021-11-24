<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (request()->input('seller')) {
            VerifyEmail::toMailUsing(function ($notifiable, $url) {
                $urlPath = trim(
                    str_replace(request()->root(), '', $url),
                    '/'
                );

                $url = config('app.seller_app_url') . '/' . $urlPath;
                return (new MailMessage)
                    ->subject('You are almost there!')
                    ->line('Click the button below to verify your email address.')
                    ->action('Verify Email Address', $url);
            });
        }

    }
}

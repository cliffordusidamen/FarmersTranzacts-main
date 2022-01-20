<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use App\Models\Page;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);

        Fortify::registerView(function () {
            $termsPage = Page::bySlug(Page::TERMS)->first();
            $privacyPage = Page::bySlug(Page::PRIVACY_POLICY)->first();

            return Inertia::render('Auth/Register', compact(
                'termsPage', 'privacyPage'
            ));
        });
    }

    /**
     * Configure the permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}

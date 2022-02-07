<?php

namespace App\Http\Middleware;

use App\Http\Resources\AgentResource;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $userAgent = request()->user() ? request()->user()->agent : null;
        if ($userAgent) {
            $userAgent->load('spendingVolume')
                ->append('is_approved')
                ->append('is_disapproved');
        }

        return array_merge(parent::share($request), [
            'product_categories' => \App\Models\ProductCategory::with('subcategories')->get(),
            'permissions' => \App\Models\Designation::getUserPermissions(),
            'userIsVendor' => authUserIsVendor(),
            'userIsAgent' => authUserIsAgent(),
            'config_app' => config('app'),
            'userAgent' => !$userAgent ? null : new AgentResource($userAgent),
            'cart' => $request->session()->get('cart'),
            'app_settings' => \App\Models\AppSetting::first(),
            'app_help_pages' => \App\Models\HelpPage::published()->get(),
            'states' => \App\Models\State::pluck('state_name', 'id'),
        ]);
    }
}

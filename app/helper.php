<?php

use App\Models\Role;
use Illuminate\Http\Request;

if (!function_exists('carbon')) {
    /**
     * Create \Carbon\Carbon dateTime object
     * 
     * @param string $dateTime
     * @param bool $returnNullIfInvalid
     * @return \Carbon\Carbon|bool
     */
    function carbon($dateTime = null, $returnFalseIfInvalid = false, $africaLagosTimeZone = false)
    {
        try {
            $timezone = $africaLagosTimeZone ? (new DateTimeZone('Africa/Lagos')) : null;
            return new \Carbon\Carbon($dateTime, $timezone);
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            return $returnFalseIfInvalid ? false : new \Carbon\Carbon();
        }
    }
}

if (!function_exists('carbon_ng')) {
    /**
     * Create \Carbon\Carbon dateTime object in Africa/Lagos timezone
     * 
     * @param string $dateTime
     * @return \Carbon\Carbon|bool
     */
    function carbon_ng($dateTime = null)
    {
        return carbon($dateTime, false, true);
    }
}

if (!function_exists('flashMessage')) {
    /**
     * Set flash message
     * 
     * @param  string  $type
     * @param  string  $message
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    function flashMessage($type, $message, ?Request $request = null)
    {
        $request = $request ?? request();

        $request->session()->flash('flash.banner', $message);
        $request->session()->flash('flash.bannerStyle', $type);

    }
}

if (!function_exists('userIsSuperAdmin')) {

    /**
     * Check if authenticated user is admin
     * 
     * @return bool
     */
    function userIsSuperAdmin(): bool
    {
        return auth()->check() && auth()->user()->is_super_admin;
    }
}

if (!function_exists('userIsAdminRole')) {

    /**
     * Check if authenticated user is admin
     * 
     * @return bool
     */
    function userIsAdminRole(): bool
    {
        return auth()->check() && auth()->user()->role_id && auth()->user()->role_id == Role::ADMIN_ID;
    }
}

if (!function_exists('authUserIsShopper')) {

    /**
     * Check if authenticated user is shopper
     * 
     * @return bool
     */
    function authUserIsShopper(): bool
    {
        return auth()->check() && auth()->user()->role_id && auth()->user()->role_id == Role::SHOPPER_ID;
    }
}

if (!function_exists('authUserIsVendor')) {

    /**
     * Check if authenticated user is vendor
     * 
     * @return bool
     */
    function authUserIsVendor(): bool
    {
        return auth()->check() && auth()->user()->role_id && auth()->user()->role_id == Role::VENDOR_ID;
    }
}
<?php

use App\Models\Designation;
use Illuminate\Http\Request;


if (!defined('DATABASE_STRING_MAXLENGTH')) {
    define('DATABASE_STRING_MAXLENGTH', 191);
}

if (!defined('INPUT_TEXTAREA_MAXLENGTH')) {
    define('INPUT_TEXTAREA_MAXLENGTH', 1000);
}

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

if (!function_exists('userIsAdminDesignation')) {

    /**
     * Check if authenticated user is admin
     * 
     * @return bool
     */
    function userIsAdminDesignation(): bool
    {
        return auth()->check() && auth()->user()->designation_id && auth()->user()->designation_id == Designation::ADMIN_ID;
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
        return auth()->check() && auth()->user()->designation_id && auth()->user()->designation_id == Designation::SHOPPER_ID;
    }
}

if (!function_exists('userVendorIsApproved')) {
    /**
     * Check if user is a vendor and has approved registration
     * 
     * @return bool
     */
    function userVendorIsApproved(): bool
    {
        if (!authUserIsVendor()) {
            return false;
        }

        return auth()->user()->vendors()->first()->approval_status == \App\Models\Vendor::STATUS_APPROVED;
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
        return auth()->check() && auth()->user()->designation_id && auth()->user()->designation_id == Designation::VENDOR_ID;
    }
}
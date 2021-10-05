<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Designation;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Create instance of the controller
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'frontend']);
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if (authUserIsShopper()) {
            return redirect('/');
        }

        if (authUserIsVendor()) {
            return $this->_vendor();
        }

        return $this->_admin();
    }

    /**
     * Display dashboard for vendor
     * 
     * @return \Illuminate\Http\Response
     */
    public function _vendor()
    {
        // Set view based on the role
        $showCategories = false;
        $user_vendor = auth()->user()->vendors()->first();
        $approvalPending = $user_vendor->approval_status == Vendor::STATUS_PENDING;
        $approvalDisapproved = $user_vendor->approval_status == Vendor::STATUS_DISAPPROVED;
        $user_vendor->append('storage_used');
        $user_vendor->append('current_month_orders');
        $storageAllowed = AppSetting::getSetting(AppSetting::VENDOR_STORAGE_LIMIT);

        $responseData = compact(
            'showCategories', 'user_vendor', 'approvalPending', 'approvalDisapproved',
            'storageAllowed',
        );

        if (request()->wantsJson()) {
            return response()->json($responseData);
        }

        return Inertia::render('Dashboards/VendorDashboard', $responseData);
    }

    /**
     * Display dashboard for admin
     * 
     * @return \Illuminate\Http\Response
     */
    public function _admin()
    {
        $showCategories = false;

        // Count shoppers, and sellers
        $shoppersCount = User::designated(Designation::SHOPPER_ID)->count();
        $sellersCount = User::designated(Designation::SELLER_ID)->count();
        // count orders, with pending
        // count products with pending approval

        $responseData = compact(
            'shoppersCount', 'sellersCount',
        );

        if (request()->wantsJson()) {
            return response()->json($responseData);
        }
        return Inertia::render('Dashboards/AdminDashboard', compact(
            'showCategories'
        ));

    }
}

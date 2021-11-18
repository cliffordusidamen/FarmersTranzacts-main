<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    /**
     * Create instance of the controller
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'frontend']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Set view based on the role
        $showCategories = false;
        $user_vendor = auth()->user()->vendors()->first();
        $approvalPending = $user_vendor->approval_status == Vendor::STATUS_PENDING;
        $approvalDisapproved = $user_vendor->approval_status == Vendor::STATUS_DISAPPROVED;
        $user_vendor->append('storage_used');
        $user_vendor->append('current_month_orders');
        $storageAllowed = AppSetting::getSetting(AppSetting::VENDOR_STORAGE_LIMIT);
        $numberOfProducts = $user_vendor->products()->count();

        return response()->json(compact(
            'approvalPending', 'approvalDisapproved',
            'showCategories','storageAllowed', 'numberOfProducts'
        ));
    }

}

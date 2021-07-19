<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
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

        return Inertia::render('Dashboards/VendorDashboard', compact(
            'showCategories', 'user_vendor', 'approvalPending', 'approvalDisapproved'
        ));
    }

    /**
     * Display dashboard for admin
     * 
     * @return \Illuminate\Http\Response
     */
    public function _admin()
    {
        $showCategories = false;
        return Inertia::render('Dashboards/AdminDashboard', compact(
            'showCategories'
        ));

    }
}

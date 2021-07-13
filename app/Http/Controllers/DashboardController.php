<?php

namespace App\Http\Controllers;

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
        // Set view based on the role
        $showCategories = false;
        return Inertia::render('Dashboards/VendorDashboard', compact('showCategories'));
    }
}

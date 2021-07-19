<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Create instance
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);

        $this->middleware('auth.vendor')->only([
            'showEditDisapprovedVendorForm',
            'saveDisapprovedVendor',
        ]);
    }

    /**
     * Show form for editing vendor registration details
     * 
     * @return \Illuminate\Http\Response
     */
    public function showEditDisapprovedVendorForm()
    {
        $user_vendor = auth()->user()->vendors()->with([
            'address', 'address.state', 'productCategories'
        ])->first();

        if ($user_vendor->is_disapproved) {
            return redirect()->route('dashboard');
        }
        $showCategories = false;

        $app_states = State::pluck('state_name', 'id');
        $product_category_ids = $user_vendor->productCategories->pluck('id', 'id');

        return Inertia::render('Profile/EditVendorRegistration', compact(
            'user_vendor', 'showCategories', 'app_states',
            'product_category_ids'
        ));
    }

    /**
     * Save updated information
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveDisapprovedVendor(Request $request)
    {
        $user_vendor = auth()->user()->vendors()->with([
            'address', 'address.state', 'productCategories'
        ])->first();

        $saved = false;
        DB::transaction(function() use ($request, &$saved, $user_vendor) {
            $user_vendor->forceFill([
                'name' => $request->input('store_name'),
                'phone' => $request->input('store_phone'),
            ]);
            $user_vendor->approval_status = Vendor::STATUS_PENDING;
            $user_vendor->save();

            $user_vendor->address->forceFill([
                'line_1' => $request->input('store_address_line_1'),
                'line_2' => $request->input('store_address_line_2'),
                'city' => $request->input('store_city'),
                'state_id' => $request->input('store_state'),
            ]);
            $user_vendor->address->save();

            $user_vendor->productCategories()->detach();
            $user_vendor->productCategories()->attach($request->input('product_categories'));

            $saved = true;
        });

        if (!$saved) {
            flashMessage('warning', 'Re-submission failed! Please, try again.');
            return back()->withInput();
        }

        flashMessage('success', 'Re-submission successful!');
        return redirect()->route('dashboard');
    }
}

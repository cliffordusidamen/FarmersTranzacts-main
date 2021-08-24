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
        $this->middleware(['auth', 'verified', 'frontend']);

        $this->middleware('auth.vendor')->only([
            'showEditDisapprovedVendorForm',
            'saveDisapprovedVendor',
            'showEditVendorForm',
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
     * Show the vendor edit form, for approved vendor
     * 
     * @return \Illuminate\Http\Response
     */
    public function showEditVendorForm()
    {
        $user_vendor = auth()->user()->vendors()->with([
            'address', 'address.state', 'productCategories'
        ])->first();

        if (!$user_vendor->is_approved) {
            return redirect()->route('dashboard');
        }
        $showCategories = false;

        $app_states = State::pluck('state_name', 'id');

        return Inertia::render('Profile/EditVendorForm', compact(
            'user_vendor', 'showCategories', 'app_states'
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

            if ($request->input('product_categories')) {
                $user_vendor->approval_status = Vendor::STATUS_PENDING;
            }
            $user_vendor->save();

            $user_vendor->address->forceFill([
                'line_1' => $request->input('store_address_line_1'),
                'line_2' => $request->input('store_address_line_2'),
                'city' => $request->input('store_city'),
                'state_id' => $request->input('store_state'),
            ]);
            $user_vendor->address->save();

            if ($request->input('product_categories')) {
                $user_vendor->productCategories()->detach();
                $user_vendor->productCategories()->attach($request->input('product_categories'));
            }

            if ($request->file('logo_image_path')) {
                $user_vendor->updateLogo($request->file('logo_image_path'));
            }

            $saved = true;
        });

        $actionDescription = $request->input('product_categories')
            ? 'Re-submission' : 'Vendor update ';

        if (!$saved) {
            flashMessage('warning', $actionDescription . ' failed! Please, try again.');
            return back()->withInput();
        }

        flashMessage('success', $actionDescription . ' successful!');
        return $request->input('product_categories')
            ? redirect()->route('dashboard')
            : back();
    }

    /**
     * Delete current vendor logo
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroyVendorLogo()
    {
        $user_vendor = auth()->user()->vendors()->first();
        if (
            $user_vendor->logo_image_path
            && $user_vendor->deleteLogo()
        ) {
            flashMessage('success', 'Logo deleted!');
        }

        return back();
    }
}

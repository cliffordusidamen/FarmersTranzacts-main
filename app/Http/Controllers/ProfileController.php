<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Agent;
use App\Models\AgentSpendingVolume;
use App\Models\Country;
use App\Models\ProductCategory;
use App\Models\State;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Create instance
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'frontend']);

        // $this->middleware('auth.vendor')->only([
        //     'showEditDisapprovedVendorForm',
        //     'saveDisapprovedVendor',
        //     'showEditVendorForm',
        // ]);
    }

    /**
     * Show form for editing registration details
     * Only available to disapproved agent registration
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showEditDisapprovedAgentForm(Request $request)
    {
        abort_if(!authUserIsAgent(), 401);
        $agent = $request->user()->agent;

        if ($agent->is_approved) {
            return redirect()->route('dashboard');
        }
        $agent->load([
            'spendingVolume',
            'productCategories',
            'address',
        ]);

        $spendingVolumes = AgentSpendingVolume::get()->keyBy('id')->map(function ($volume) {
            return number_format($volume->minimum_amount)
                . ($volume->maximum_amount
                    ? " - " . number_format($volume->maximum_amount)
                    : '+');
        });
        $app_states = State::pluck('state_name', 'id');
        $product_category_ids = $agent->productCategories->pluck('id', 'id');
        
        return Inertia::render('Profile/EditDisapprovedAgent', compact(
            'agent', 'app_states', 'product_category_ids',
            'spendingVolumes',
        ));
    }

    /**
     * Save updated information
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resubmitForApproval(Request $request)
    {
        $agent = $request->user()->agent;
        $uniqueBizNameRule = Rule::unique('agents', 'business_name')
            ->ignore($agent->id);

        $this->validate($request, [
            'business_name' => ['required', 'max:150', $uniqueBizNameRule],
            'spending_volume_id' => ['required', 'exists:agent_spending_volumes,id'],
            'store_address_line_1' => ['required', 'min:5', 'max:50'],
            'store_address_line_2' => ['nullable', 'min:5', 'max:50'],
            'store_city' => ['nullable', 'min:3', 'max:50'],
            'store_state' => ['required', 'exists:states,id'],
            'product_categories' => ['array', 'min:1'],
        ]);

        $saved = false;

        DB::transaction(function() use ($request, &$saved, &$agent) {

            $agent->forceFill([
                'business_name' => $request->input('business_name'),
                'spending_volume_id' => $request->input('spending_volume_id'),
                'approval_status' => Agent::STATUS_PENDING,
            ]);
            $agent->save();

            $agent->productCategories()->detach();
            $agent->productCategories()->attach($request->input('product_categories'));

            $agent->address->forceFill([
                'line_1' => $request->input('store_address_line_1'),
                'line_2' => $request->input('store_address_line_2'),
                'city' => $request->input('store_city'),
                'state_id' => $request->input('store_state'),
            ]);

            $agent->address->save();

            $saved = true;

        });

        if ($request->wantsJson()) {
            if (!$saved) {
                return response()->json([
                    'save_failed' => true,
                    'message' => 'Registration details not saved! Please, try again.'
                ]);
            }

            return response()->json([
                'message' => 'Registration details updated!',
            ]);
        }

        $actionDescription = $request->input('product_categories')
            ? 'Re-submission' : 'Information update ';

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
     * Show form for editing vendor registration details
     * 
     * @return \Illuminate\Http\Response
     */
    public function showEditDisapprovedVendorForm()
    {
        $user_vendor = auth()->user()->vendors()->with([
            'address', 'address.state', 'productCategories'
        ])->first();

        if (!$user_vendor->is_disapproved && !request()->wantsJson()) {
            return redirect()->route('dashboard');
        }
        $showCategories = false;

        $app_states = State::pluck('state_name', 'id');
        $product_category_ids = $user_vendor->productCategories->pluck('id', 'id');

        $responseData = compact(
            'user_vendor', 'showCategories', 'app_states',
            'product_category_ids'
        );

        if (request()->wantsJson()) {
            $responseData['states'] = $app_states;
            $responseData['countries'] = Country::pluck('name', 'id');
            $responseData['product_categories'] = ProductCategory::with('subcategories')->get();

            return response()->json($responseData);
        }

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

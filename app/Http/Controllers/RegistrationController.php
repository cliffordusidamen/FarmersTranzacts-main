<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShopperRequest;
use App\Http\Requests\StoreVendorRequest;
use App\Models\Address;
use App\Models\Country;
use App\Models\Designation;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\State;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    /**
     * Show the form for registering as shopper
     * 
     * @return \Illuminate\Http\Response
     */
    public function showShopperForm()
    {
        return Inertia::render('Registration/Shopper');
    }

    /**
     * Save the vendor information to the database
     * 
     * @param \App\Http\Requests\StoreShopperRequest  $request
     * @return  \Illuminate\Http\Response
     */
    public function saveShopper(StoreShopperRequest $request)
    {
        $user = User::create($request->only([
            'first_name', 'last_name', 'email', 'phone',
            'password',
        ]));

        if (!$user) {
            flashMessage('warning', 'Registration failed! Please, try again.');
            return back()->withInput();
        }

        event(new Registered($user));
        flashMessage('success', 'Registration successfull! Please, check you email in click on the verification link.');
        return back();
    }

    /**
     * Show the form for registering as vendor
     * 
     * @return \Illuminate\Http\Response
     */
    public function showVendorForm()
    {
        $app_states = State::pluck('state_name', 'id');

        if (request()->wantsJson()) {
            $product_categories = ProductCategory::with('subcategories')->get();
            $countries = Country::pluck('name', 'id');

            return response()->json(compact(
                'app_states',
                'product_categories',
                'countries',
            ));
        }
        return Inertia::render('Registration/Vendor', compact('app_states'));
    }

    /**
     * Save the vendor information to the database
     * 
     * @param \App\Http\Requests\StoreVendorRequest  $request
     * @return  \Illuminate\Http\Response
     */
    public function saveVendor(StoreVendorRequest $request)
    {
        // Run in transaction
        $user = false;
        DB::transaction(function() use ($request, &$user) {

            $userData = $request->only([
                'first_name', 'last_name', 'phone', 'email', 'password',
            ]);
            $userData['designation_id'] = Designation::SELLER_ID;
            $user = User::create($userData);

            $vendor = Vendor::create([
                'shop_name' => $request->input('shop_name'),
                'business_name' => $request->input('business_name'),
                'tin' => $request->input('tin'),
                'ownership_type' => $request->input('ownership_type'),
                'entity_country_id' => $request->input('entity_country_id'),
            ]);

            $vendor->productCategories()->attach($request->input('product_categories'));

            $address = Address::create([
                'vendor_id' => $vendor->id,
                'line_1' => $request->input('address_line_1'),
                'line_2' => $request->input('address_line_2'),
                'city' => $request->input('city'),
                'state_id' => $request->input('state_id'),
                'country_id' => $request->input('shipping_country_id'),
                'postal_code' => $request->input('postal_code'),
            ]);

            $user->vendors()->attach($vendor);

        });

        if (!$user) {
            flashMessage('warning', 'Registration failed! Please, try again.');
            return back()->withInput();
        }

        event(new Registered($user));
        flashMessage('success', 'Registration successful! Please, check you email in click on the verification link.');
        return back();
    }
}

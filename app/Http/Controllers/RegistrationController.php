<?php

namespace App\Http\Controllers;

use App\Events\AgentRegistered;
use App\Http\Requests\StoreShopperRequest;
use App\Http\Requests\StoreAgentRequest;
use App\Models\Address;
use App\Models\Country;
use App\Models\Designation;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\State;
use App\Models\User;
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
     * Show the form for registering as agent
     * 
     * @return \Illuminate\Http\Response
     */
    public function showAgentForm()
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
        return Inertia::render('Registration/Agent', compact('app_states'));
    }

    /**
     * Save the vendor information to the database
     * 
     * @param \App\Http\Requests\StoreAgentRequest  $request
     * @return  \Illuminate\Http\Response
     */
    public function saveAgent(StoreAgentRequest $request)
    {
        $user = false;
        DB::transaction(function() use ($request, &$user) {

            $userData = $request->only([
                'first_name', 'last_name', 'phone', 'email', 'password',
                'business_name',
            ]);
            $userData['designation_id'] = Designation::AGENT_ID;
            $user = User::create($userData);

            $user->interestedProductCategories()
                ->attach($request->input('product_categories'));

            $address = Address::create([
                'user_id' => $user->id,
                'line_1' => $request->input('address_line_1'),
                'line_2' => $request->input('address_line_2'),
                'city' => $request->input('city'),
                'state_id' => $request->input('state_id'),
                'postal_code' => $request->input('postal_code'),
            ]);

        });

        if ($request->wantsJson()) {
            if (!$user) {
                return response()->json([
                    'failed' => true,
                ]);
            }

            event(new AgentRegistered($user));
            return response()->json(compact('user'));
        }

        if (!$user) {
            flashMessage('warning', 'Registration failed! Please, try again.');
            return back()->withInput();
        }

        event(new AgentRegistered($user));
        flashMessage('success', 'Registration successful! Please, check you email in click on the verification link.');
        return back();
    }
}

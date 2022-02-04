<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReferralRequest;
use App\Models\ReferralRequest;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReferralController extends Controller
{
    
    /**
     * Create instance of the controller
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    /**
     * Display a listing of current user referrals.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $referralRequests = auth()
            ->user()
            ->referralRequests()
            ->with(['state'])
            ->paginate(15);

        return Inertia::render('Referral/Index', compact('referralRequests'));
    }

    /**
     * Display form for creating referral request
     * 
     * @return \Illuminate\Http\Response
     */
    public function showRequestForm()
    {
        $app_states = State::pluck('state_name', 'id');

        return Inertia::render('Referral/Create', compact('app_states'));
    }

    /**
     * Create referral request and store in the database
     * @param  \App\Http\Requests\StoreReferralRequest $request
     * @return \Illuminate\Http\Response
     */
    public function storeRequest(StoreReferralRequest $request)
    {
        $expectedFields = array_keys(ReferralRequest::defaultValidationRules());
        $referralRequest = $request->user()
            ->referralRequests()
            ->withAppend(['can_edit'])
            ->create($request->only(
                $expectedFields
            ));

        if ($referralRequest) {
            flashMessage('success', 'New farmer referral submitted!');
            return redirect(route('referrals.index'));
        }

        flashMessage('danger', "Farmer referral information not saved.Please, try again.");
        return back()->withInput();
    }
}

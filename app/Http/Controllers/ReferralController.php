<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReferralRequest;
use App\Models\ReferralRequest;
use App\Models\State;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            ->latest()
            ->paginate(15);

        $referralRequests->each(fn ($referralRequest) => $referralRequest->append('is_editable'));

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
            ->create($request->only(
                $expectedFields
            ));
            
        $referralRequest->append(['is_editable']);

        if ($referralRequest) {
            flashMessage('success', 'New farmer referral submitted!');
            return redirect(route('referrals.index'));
        }

        flashMessage('danger', "Farmer referral information not saved.Please, try again.");
        return back()->withInput();
    }

    /**
     * View primary details of a referral
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $referralRequest = auth()
            ->user()
            ->referralRequests()
            ->with('state')
            ->findOrFail($id);

        $referralRequest->append(['full_name', 'is_editable', 'status_number']);

        return Inertia::render('Referral/Show', compact('referralRequest'));
    }

    /**
     * Display form for editing a referral
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $referralRequest = auth()
            ->user()
            ->referralRequests()
            ->with('state')
            ->editable()
            ->findOrFail($id);

        $app_states = State::pluck('state_name', 'id');

        $referralRequest->append(['full_name', 'status_number']);

        return Inertia::render('Referral/Edit', compact(
            'referralRequest', 'app_states'
        ));
    }

    /**
     * Update the referral request
     * 
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request  $request)
    {
        $referralRequest = auth()
            ->user()
            ->referralRequests()
            ->with('state')
            ->editable()
            ->findOrFail($id);

        $rules = ReferralRequest::defaultValidationRules($id);

        $this->validate($request, $rules);

        $referralRequest->forceFill($request->only(array_keys($rules)));

        if ($referralRequest->save()) {
            flashMessage('success', 'Farmer information updated!');
            return redirect(route('referrals.index'));
        }

        flashMessage('danger', 'Farmer information was not updated! Please, try again.');
        return back();
    }

    

    /**
     * Update the referral request
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $referralRequest = auth()
            ->user()
            ->referralRequests()
            ->editable()
            ->findOrFail($id);

        if ($referralRequest->delete()) {
            flashMessage('success', 'Farmer referral request deleted!');
            return redirect(route('referrals.index'));
        }

        flashMessage('danger', 'Farmer referral request was not deleted! Please, try again.');
        return back();
    }

    /**
     * Search farmer referrals
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $queryName = trim($request->input('name', ''));
        $queryState = trim($request->input('state', ''));
        $app_states = State::pluck('state_name', 'id');

        $referrals = [];
        $isSearch = false;
        
        if (!empty($queryName) || is_numeric($queryState)) {
            $isSearch = true;
            $referrals = ReferralRequest::with('state')
                ->where(function(Builder $query) use ($queryName, $queryState) {
                    if (!empty($queryName)) {
                        $query->where(DB::raw('CONCAT(last_name, " ", first_name, " ", middle_name)'), 'LIKE', "%{$queryName}%")
                            ->orWhere(DB::raw('CONCAT(last_name, " ", middle_name, " ", first_name)'), 'LIKE', "%{$queryName}%")
                            ->orWhere(DB::raw('CONCAT(last_name, " ", first_name)'), 'LIKE', "%{$queryName}%")
                            ->orWhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', "%{$queryName}%");
                    }

                    if (!empty($queryState)) {
                        $query->where('state_id', (int) $queryState);
                    }
                })
                ->latest()
                ->paginate(15);
        }

        return Inertia::render('Referral/Search', compact(
            'referrals', 'isSearch', 'queryName', 'queryState', 'app_states'
        ));
    }
}

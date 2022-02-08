<?php

namespace App\Http\Controllers;

use App\Models\ReferralRequest;
use App\Models\State;
use Illuminate\Http\Request;

class ReferralRequestController extends Controller
{
    /**
     * Create instance of the controller
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'frontend']);
    }

    /**
     * Get all referral requests
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchText = trim(request()->query('q', ''));
        $searchState = trim(request()->query('state', ''));
        $app_states = State::pluck('state_name', 'id');

        $isSearch = !empty($searchText) || is_numeric($searchState);
        $referrals = ReferralRequest::with(['state', 'user'])
            ->searchName($searchText)
            ->byState((int) $searchState)
            ->latest()
            ->paginate(15);

        return response()->json(compact(
            'searchText', 'searchState', 'isSearch', 'referrals', 'app_states'
        ));
    }

    /**
     * Get details of a referral
     * 
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(int $id, Request $request)
    {
        $referral = ReferralRequest::with([
                'state',
                'user'
            ])
            ->findOrFail($id);

        $referral->append([
            'full_name',
            'status_number'
        ]);

        return response()->json(compact('referral'));
    }
}

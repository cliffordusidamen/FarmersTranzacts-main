<?php

namespace App\Http\Controllers;

use App\Events\ReferralApprovedEvent;
use App\Events\ReferralDisapprovedEvent;
use App\Models\ReferralRequest;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

        $referrals->each(fn($referral) => $referral->append([
            'full_name', 'status_number', 'last_season'
        ]));

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
            'status_number',
        ]);

        $seasons = [];

        if (!empty($referral->farm_id)) {
            $seasons = $referral->farm->seasons;
        }

        return response()->json(compact('referral', 'seasons'));
    }

    
    /**
     * Approve a referral request
     * 
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function approve(int $id, Request $request)
    {
        $referral = ReferralRequest::pendingApproval()->findOrFail($id);
        $approved = false;

        DB::transaction(function() use ($referral, &$approved) {
            $referral->approval_status = ReferralRequest::STATUS_APPR0VED;

            if ($referral->save()) {
                event(new ReferralApprovedEvent($referral));
            }
            $approved = true;
        });

        if ($approved) {
            return response()->json(compact('referral'));
        }

        return response()->json([
            'failed' => true,
        ]);
    }

    
    /**
     * Disapprove a referral request
     * 
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function disapprove(int $id, Request $request)
    {
        $referral = ReferralRequest::pendingApproval()->findOrFail($id);
        $disapproved = false;

        DB::transaction(function() use ($referral, &$disapproved) {
            $referral->approval_status = ReferralRequest::STATUS_DISAPPROVED;
            $referral->disapproval_reason = request('disapproval_reason');

            if ($referral->save()) {
                event(new ReferralDisapprovedEvent($referral));
            }
            $disapproved = true;
        });

        if ($disapproved) {
            return response()->json(compact('referral'));
        }

        return response()->json([
            'failed' => true,
        ]);
    }
    
    /**
     * Get details of a referral farm season
     * 
     * @param  int  $id
     * @param  int  $seasonId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showSeason(
        int $id,
        int $seasonId,
        Request $request
    ) {
        $referral = ReferralRequest::with([
                'state',
                'user'
            ])
            ->findOrFail($id);

        $referral->append([
            'full_name',
            'status_number',
        ]);

        $season = $referral->farm->seasons()->findOrFail($seasonId);

        return response()->json(compact('referral', 'season'));
    }
}

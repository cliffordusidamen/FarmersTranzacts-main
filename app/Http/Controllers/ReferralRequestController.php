<?php

namespace App\Http\Controllers;

use App\Events\ReferralApprovedEvent;
use App\Events\ReferralDisapprovedEvent;
use App\Exports\FarmRecordsExport;
use App\Models\Farm;
use App\Models\ReferralRequest;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReferralRequestController extends Controller
{
    /**
     * Create instance of the controller
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'frontend'])
            ->except(['downloadSeasonRecords']);
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
                'user',
                'localGovernmentArea',
                'farm',
            ])
            ->findOrFail($id);

        $referral->append([
            'full_name',
            'status_number',
        ]);

        $referral->farm->load([
            'localGovernmentArea',
        ]);

        $seasons = $referral->farm->seasons()->with([
            'seasonInputs',
            'seasonProduces',
        ])->get();

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

    /**
     * Generate temporary signed url for downloading season information of a particular farm
     * 
     * @param  int  $referralRequestId
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateSeasonDownloadUrl($referralRequestId)
    {
        $referral = ReferralRequest::with(['farm'])
            ->findOrFail($referralRequestId);

        $url = null;
        $message = '';

        if ($referral->farm->has('seasons')) {
            $url = URL::temporarySignedRoute(
                'referral_requests.download_season_records',
                now()->addMinutes(3),
                ['farm' => $referral->farm->id]
            );
        } else {
            $message = 'No season records found!';
        }

        return response()->json(compact('url', 'message'));
    }

    /**
     * Download farm season records for the indicated farm
     *
     * @param  Farm  $farm
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function downloadSeasonRecords(
        Farm $farm,
        Request $request
    ) {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $fileName = $farm->id . '_season_records.xlsx';

        return (new FarmRecordsExport($farm))->download($fileName);
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\FarmerReferralEvent;
use App\Http\Requests\StoreFarmSeasonRequest;
use App\Http\Requests\StoreReferralRequest;
use App\Models\FarmSeason;
use App\Models\ReferralRequest;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MyReferralController extends Controller
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

        $referralRequests->each(fn ($referralRequest) => (
            $referralRequest->append([
                'is_editable',
                'is_deletable',
                'status_number'
            ])
        ));

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
        $prevYear = date('Y') - 1;
        $curYear = date('Y');
        $years = [
            $prevYear => $prevYear,
            $curYear => $curYear
        ];

        $seasons = FarmSeason::getSeasons(true);

        return Inertia::render('Referral/Create', compact(
            'app_states', 'years', 'seasons'
        ));
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
            
        $referralRequest->append(['is_editable', 'is_deletable']);

        if ($referralRequest) {
            event(new FarmerReferralEvent($referralRequest));
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

        $referralRequest->append([
            'full_name',
            'is_editable',
            'is_deletable',
            'status_number',
        ]);

        $seasons = $referralRequest->farm_id
            ? $referralRequest->farm->seasons
            : [];

        return Inertia::render('Referral/Show', compact(
            'referralRequest', 'seasons'
        ));
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
        $prevYear = date('Y') - 1;
        $curYear = date('Y');
        $years = [
            $prevYear => $prevYear,
            $curYear => $curYear
        ];

        $seasons = FarmSeason::getSeasons(true);

        $referralRequest->append(['full_name', 'status_number']);

        return Inertia::render('Referral/Edit', compact(
            'referralRequest', 'app_states', 'years', 'seasons'
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
        $referralRequest->approval_status = ReferralRequest::STATUS_PENDING;

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
                ->searchName($queryName)
                ->byState($queryState)
                ->latest()
                ->paginate(15);
        }

        return Inertia::render('Referral/Search', compact(
            'referrals', 'isSearch', 'queryName', 'queryState', 'app_states'
        ));
    }

    /**
     * Create new farm season record for the farm
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createSeason(int $id)
    {
        $referralRequest = auth()
            ->user()
            ->referralRequests()
            ->approved()
            ->findOrFail($id);

        $referralRequest->load('farm');
        $referralRequest->farm->append(['last_season']);

        $referralRequest->append(['full_name']);
        $prevYear = date('Y') - 1;
        $curYear = date('Y');
        $years = [
            $prevYear => $prevYear,
            $curYear => $curYear
        ];

        $seasons = FarmSeason::getSeasons(true);

        return Inertia::render('Referral/NewSeason', compact(
            'referralRequest', 'years', 'seasons'
        ));
    }

    /**
     * Store new farm season record for the farm
     * 
     * @param  \App\Http\Requests\StoreFarmSeasonRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeSeason(int $id, StoreFarmSeasonRequest $request)
    {
        $referralRequest = auth()
            ->user()
            ->referralRequests()
            ->approved()
            ->findOrFail($id);

        $rules = FarmSeason::defaultValidationRules();

        $this->validate($request, $rules);
        $alreadyHasSeason = $referralRequest->farm->alreadyHasSeason($request->year, $request->season);

        if ($alreadyHasSeason) {
            flashMessage('danger', 'Season record for the selected year and season already exists!');
            return back();
        }

        $farmSeason = $referralRequest->farm->seasons()->create($request->only(array_keys($rules)));

        if ($farmSeason->id) {
            flashMessage('success', 'Farm season created!');
            return redirect(route('referrals.show', $referralRequest->id));
        }

        flashMessage('danger', 'Farm season was not created! Please, try again.');
        return back();
    }
}

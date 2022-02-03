<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaqSaveRequest;
use App\Http\Requests\SaveBannerRequest;
use App\Http\Requests\StoreSalesCommissionRequest;
use App\Http\Requests\StoreSpendingVolumeRequest;
use App\Models\AgentSpendingVolume;
use App\Models\AppSetting;
use App\Models\Banner;
use App\Models\Faq;
use App\Models\HelpPage;
use App\Models\Page;
use App\Models\SalesCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SetupController extends Controller
{
    /**
     * Get settings data
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function settings(Request $request)
    {
        abort_if(!userIsSuperAdmin() && !userIsAdminDesignation(), 404);

        $settings = AppSetting::first();

        return response()->json(compact('settings'));
    }

    /**
     * Update settings in the database
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSettings(Request  $request)
    {
        abort_if(!userIsSuperAdmin() && !userIsAdminDesignation(), 404);
        $this->validate($request, AppSetting::getDefaultValidation());

        $settings = AppSetting::first();
        $saved = $settings->forceFill($request->all())->save();

        if (!$saved) {
            throw new \Exception('Settings not updated! Please, try again.');
        }
        return response()->json();
    }

    /**
     * Get all page contents from the database
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pages(Request $request)
    {
        abort_if(!userIsSuperAdmin() && !userIsAdminDesignation(), 404);

        $pages = Page::get();

        return response()->json(compact('pages'));
    }

    /**
     * Get page by permalink/slug
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function showPage(Request $request, string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return response()->json(compact('page'));
    }

    /**
     * Get page by id, for editing
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPage(Request $request, int $id)
    {
        abort_if(!userIsSuperAdmin() && !userIsAdminDesignation(), 404);

        $page = Page::findOrFail($id);

        return response()->json(compact('page'));
    }

    /**
     * Get page by id, for editing
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePage(Request $request, int $id)
    {
        abort_if(!userIsSuperAdmin() && !userIsAdminDesignation(), 404);

        $page = Page::findOrFail($request->id);

        $this->validate($request, [
            'title' => 'required|string|min:3',
            'content' => 'sometimes|nullable|string',
        ]);

        $page->content = $request->content;
        $page->title = $request->title;

        $saved = $page->save();

        return response()->json(compact('page', 'saved'));
    }

    /**
     * Get all the available homepage banners
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function banners(Request $request)
    {
        abort_if(!userIsSuperAdmin() && !userIsAdminDesignation(), 404);

        $banners = Banner::get();

        if ($banners->count()) {
            $banners->each(fn($banner) => (
                $banner->append('image_url')
            ));
        }

        return response()->json(compact('banners'));
    }

    /**
     * Create a banner
     * 
     * @param  \App\Http\Requests\SaveBannerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createBanner(SaveBannerRequest $request)
    {
        $banner = new Banner($request->only('name'));

        $saved = false;

        DB::transaction(function () use ($banner, $request, &$saved) {
            $banner->saveBannerImage($request->file('image'));
            if (!$banner->save()) {
                $banner->deleteBannerImage();
            }

            $saved = true;
        });

        if (!$saved) {
            throw new \Exception('Banner not created! Please, try again.');
        }

        return response()->json(compact('banner'));
    }

    /**
     * Fetch banner details for edit
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editBanner(Request $request, int $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->append('image_url');
        return response()->json(compact('banner'));
    }

    /**
     * Update banner details
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateBanner(Request $request, int $id)
    {
        $this->validate($request, Banner::validationRules($id));
        $banner = Banner::findOrFail($id);
        $saved = true;

        DB::transaction(function () use ($banner, $request, &$saved) {
            $banner->name = $request->input('name');
            $banner->save();
            $banner->saveBannerImage($request->file('image'));

            $saved = true;
        });

        if (!$saved) {
            throw new \Exception('Banner not updated! Please, try again.');
        }

        return response()->json(compact('banner'));
    }

    /**
     * Delete a particular banner, with its image
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteBanner(int $id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->deleteWithImage()) {
            return response()->json();
        }

        throw new \Exception('Banner not deleted! Please, try again.');
    }

    /**
     * List all available agent commissions setup
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function agentCommissions(Request $request)
    {
        abort_if(!userIsSuperAdmin() && !userIsAdminDesignation(), 404);

        $spendingVolumes = AgentSpendingVolume::withCount('agents')->get();
        $commissionPerReferral = AppSetting::getSetting(AppSetting::COMMISSION_PER_REFERRAL);

        return response()->json(compact('spendingVolumes', 'commissionPerReferral'));
    }

    /**
     * Store new agent commissions setup
     * 
     * @param \App\Http\Requests\StoreSpendingVolumeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAgentCommissions(StoreSpendingVolumeRequest $request)
    {
        if (AgentSpendingVolume::checkAmount($request->minimum_amount)) {
            throw ValidationException::withMessages([
                'minimum_amount' => 'There is another commission setup that covers this quantity',
            ]);
        }

        if (AgentSpendingVolume::checkAmount($request->maximum_amount)) {
            throw ValidationException::withMessages([
                'maximum_amount' => 'There is another commission setup that covers this quantity',
            ]);
        }

        $spendingVolume = AgentSpendingVolume::create($request->only([
            'minimum_amount', 'maximum_amount', 'commission_percentage',
        ]));

        if (!$spendingVolume) {
            throw new \Exception('Commission setup not saved! Please, try again.');
        }

        return response()->json(compact('spendingVolume'));
    }

    /**
     * Get details for editing an agent commission setup
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editAgentCommissions(Request $request, int $id)
    {
        $spendingVolume = AgentSpendingVolume::findOrFail($id);

        return response()->json(compact('spendingVolume'));
    }

    /**
     * Store new agent commissions setup
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAgentCommissions(
        Request $request,
        int $id
    ) {
        $spendingVolume = AgentSpendingVolume::findOrFail($id);
        $this->validate($request, AgentSpendingVolume::validationRules($id));

        $minAmountClash = AgentSpendingVolume::checkAmount($request->minimum_amount);
        if ($minAmountClash && $spendingVolume->id != $minAmountClash->id) {
            throw ValidationException::withMessages([
                'minimum_amount' => 'There is another commission setup that covers this amount',
            ]);
        }

        $spendingVolume = AgentSpendingVolume::findOrFail($id);
        $maxAmountClash = AgentSpendingVolume::checkAmount($request->maximum_amount);
        if ($maxAmountClash && $spendingVolume->id != $maxAmountClash->id) {
            throw ValidationException::withMessages([
                'maximum_amount' => 'There is another commission setup that covers this amount',
            ]);
        }

        $spendingVolume->forceFill($request->only([
            'minimum_amount', 'maximum_amount', 'commission_percentage',
        ]));

        if (!$spendingVolume->save()) {
            throw new \Exception('Commission setup not saved! Please, try again.');
        }

        return response()->json(compact('spendingVolume'));
    }

    /**
     * Delete an agent commission setup
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteAgentCommission(int $id)
    {
        $spendingVolume = AgentSpendingVolume::findOrFail($id);

        if ($spendingVolume->agents()->count()) {
            throw new \Exception('Commission setup already in use and cannot be deleted');
        }

        if ($spendingVolume->delete()) {
            return response()->json(['success' => true]);
        }

        throw new \Exception('Commission setup not deleted! Please, try again.');
    }

    /**
     * List all available sales commissions setup
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salesCommissions(Request $request)
    {
        abort_if(!userIsSuperAdmin() && !userIsAdminDesignation(), 404);

        $salesCommissions = SalesCommission::get();

        return response()->json(compact('salesCommissions'));
    }

    /**
     * Store new sales commissions setup
     * 
     * @param \App\Http\Requests\StoreSalesCommissionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSalesCommissions(StoreSalesCommissionRequest $request)
    {
        if (SalesCommission::checkAmount($request->minimum_amount)) {
            throw ValidationException::withMessages([
                'minimum_amount' => 'There is another commission setup that covers this quantity',
            ]);
        }

        if (SalesCommission::checkAmount($request->maximum_amount)) {
            throw ValidationException::withMessages([
                'maximum_amount' => 'There is another commission setup that covers this quantity',
            ]);
        }

        $salesCommission = SalesCommission::create($request->only([
            'minimum_amount', 'maximum_amount', 'percentage',
        ]));

        if (!$salesCommission) {
            throw new \Exception('Commission setup not saved! Please, try again.');
        }

        return response()->json(compact('salesCommission'));
    }

    /**
     * Get details for editing an sales commission setup
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editSalesCommissions(Request $request, int $id)
    {
        $salesCommission = SalesCommission::findOrFail($id);

        return response()->json(compact('salesCommission'));
    }

    /**
     * Store new sales commissions setup
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSalesCommissions(
        Request $request,
        int $id
    ) {
        $salesCommission = SalesCommission::findOrFail($id);
        $this->validate($request, SalesCommission::validationRules());

        $minAmountClash = SalesCommission::checkAmount($request->minimum_amount);
        if ($minAmountClash && $salesCommission->id != $minAmountClash->id) {
            throw ValidationException::withMessages([
                'minimum_amount' => 'There is another commission setup that covers this amount',
            ]);
        }

        $salesCommission = SalesCommission::findOrFail($id);
        $maxAmountClash = SalesCommission::checkAmount($request->maximum_amount);
        if ($maxAmountClash && $salesCommission->id != $maxAmountClash->id) {
            throw ValidationException::withMessages([
                'maximum_amount' => 'There is another commission setup that covers this amount',
            ]);
        }

        $salesCommission->forceFill($request->only([
            'minimum_amount', 'maximum_amount', 'percentage',
        ]));

        if (!$salesCommission->save()) {
            throw new \Exception('Commission setup not saved! Please, try again.');
        }

        return response()->json(compact('salesCommission'));
    }

    /**
     * Delete an sales commission setup
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSalesCommission(int $id)
    {
        $spendingVolume = SalesCommission::findOrFail($id);

        if ($spendingVolume->delete()) {
            return response()->json(['success' => true]);
        }

        throw new \Exception('Commission setup not deleted! Please, try again.');
    }

    /**
     * Get a listing of all help pages
     * 
     * @return \Illuminate\Http\Response
     */
    public function helpPages()
    {
        $helpPages = HelpPage::get();
        return response()->json(compact('helpPages'));
    }

    /**
     * Store help page
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeHelpPage(Request $request)
    {
        $data = $this->validate($request, HelpPage::validationRules());
        $helpPage = HelpPage::create($data);

        if (!$helpPage) {
            throw new \Exception('Help page not saved! Please, try again.');
        }

        return response()->json(compact('helpPage'));
    }

    /**
     * Get details of a help page
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showHelpPage(Request $request, int $id)
    {
        $helpPage = HelpPage::findOrFail($id);
        return response()->json(compact('helpPage'));
    }

    /**
     * Update the help page
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateHelpPage(Request $request, int $id)
    {
        $helpPage = HelpPage::findOrFail($id);
        $data = $this->validate($request, HelpPage::validationRules((int) $id));
        $helpPage->forceFill($data);

        if (!$helpPage->save()) {
            throw new \Exception('Help page not saved! Please, try again.');
        }

        return response()->json(compact('helpPage'));
    }

    /**
     * Delete an help page
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteHelpPage(int $id)
    {
        $helpPage = HelpPage::findOrFail($id);

        if ($helpPage->delete()) {
            return response()->json(['success' => true]);
        }

        throw new \Exception('Help page not deleted! Please, try again.');
    }

    /**
     * Get a listing of all frequently asked questions
     * 
     * @return \Illuminate\Http\Response
     */
    public function faqs()
    {
        $faqs = Faq::with('helpPage')->get();
        return response()->json(compact('faqs'));
    }

    /**
     * Create frequently asked question
     * 
     * @return \Illuminate\Http\Response
     */
    public function faqsCreate()
    {
        $helpPages = HelpPage::pluck('title', 'id');
        return response()->json(compact('helpPages'));
    }

    /**
     * Store frequently asked question to the database
     * 
     * @param  \App\Http\Requests\FaqSaveRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function faqsStore(FaqSaveRequest $request)
    {
        $data = collect($request->all())->only(array_keys(
            Faq::defaultValidationRules()
        ))->all();

        $faq = Faq::create($data);

        if (!$faq) {
            throw new \Exception('FAQ not saved! Please try again.');
        }

        return response()->json(compact('faq'));
    }

    /**
     * Get data of frequently asked question for edit
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function faqsEdit(Request $request, int $id)
    {
        $faq = Faq::findOrFail($id);
        $helpPages = HelpPage::pluck('title', 'id');
        return response()->json(compact('faq', 'helpPages'));

    }

    /**
     * Update frequently asked question
     * 
     * @param  \App\Http\Requests\FaqSaveRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function faqsUpdate(FaqSaveRequest $request, int $id)
    {
        $faq = Faq::findOrFail($id);
        $data = collect($request->all())->only(array_keys(
            Faq::defaultValidationRules()
        ))->all();
        $updated = $faq->forceFill($data)->save();

        if (!$updated) {
            throw new \Exception('FAQ not saved! Please try again.');
        }

        return response()->json(compact('faq'));
    }

    /**
     * Delete a frequently asked question to the database
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function faqsDelete(
        int $id
    ) {
        $faq = Faq::findOrFail($id);

        if ($faq->delete()) {
            return response()->json(['success' => true]);
        }

        throw new \Exception('FAQ not deleted! Please, try again.');

    }

    /**
     * Update agent referral commission
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAgentReferralCommission(Request $request)
    {
        abort_if(!userIsSuperAdmin() && !userIsAdminDesignation(), 404);
        $this->validate($request, [
            'commission_per_referral' => 'required|numeric|min:0|max:9999999',
        ]);

        $settings = AppSetting::first();
        $settings->commission_per_referral = $request->commission_per_referral;

        if (!$settings->save()) {
            throw new \Exception('Settings not updated! Please, try again.');
        }
        return response()->json();
    }
}

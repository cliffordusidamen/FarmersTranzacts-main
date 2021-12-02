<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveBannerRequest;
use App\Http\Requests\StoreSpendingVolumeRequest;
use App\Models\AgentSpendingVolume;
use App\Models\AppSetting;
use App\Models\Banner;
use App\Models\Page;
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
        // TO DO: Implement validation
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

        return response()->json(compact('spendingVolumes'));
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
}

<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
}

<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
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
}

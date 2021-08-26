<?php

namespace App\Http\Middleware;

use App\Models\Designation;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class HandleFrontendAppRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $this->_attachDataForAdminApp($request, $response);
        $this->_attachDataForSellerApp($request, $response);
        // $responseData = array_merge($response->getData(true), [
        //     'extra_data' => 'Hello, From middlewatre!'
        // ]);
        // $response->setData($responseData);
        return $response;
    }

    /**
     * Attach data of requests from admin frontend
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function _attachDataForSellerApp(Request $request, &$response)
    {
        $requestFromSellerApp = $request->user()->designation_id == Designation::SELLER_ID;
        if (!$requestFromSellerApp || !method_exists($response, 'getData')) {
            return;
        }

        $user = User::find($request->user()->getKey());
        $user->profile_photo_url;
        $user->vendorData = $user->vendor_data;

        $responseData = array_merge($response->getData(true), $user->toArray());
        $response->setData($responseData);
    }

    /**
     * Attach data of requests from admin frontend
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function _attachDataForAdminApp(Request $request, &$response)
    {
        $requestFromAdminApp = $request->user()->designation_id == Designation::STAFF_ID
            || $request->user()->is_super_admin;

        if (!$requestFromAdminApp || !method_exists($response, 'getData')) {
            return;
        }

        $user = User::find($request->user()->getKey());
        $user->profile_photo_url;

        $unapproved_vendors = \App\Models\Vendor::awaitingApproval()
            ->with(['users'])
            ->paginate(5)
            ->toArray();

        $responseData = array_merge($user->toArray(), $response->getData(true), compact('unapproved_vendors'));
        $response->setData($responseData);
    }
}

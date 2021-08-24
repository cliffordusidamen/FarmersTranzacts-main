<?php

namespace App\Http\Middleware;

use App\Models\Designation;
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

        $vendorData = $request->user()
            ->vendors()
            ->with(['productCategories', 'address'])
            ->first()
            ->toArray();

        $responseData = array_merge($response->getData(true), compact('vendorData'));
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

        $unapproved_vendors = \App\Models\Vendor::awaitingApproval()
            ->with(['users'])
            ->paginate(5)
            ->toArray();

        $responseData = array_merge($response->getData(true), compact('unapproved_vendors'));
        $response->setData($responseData);
    }
}

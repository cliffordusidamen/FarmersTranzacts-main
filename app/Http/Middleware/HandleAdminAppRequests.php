<?php

namespace App\Http\Middleware;

use App\Models\Designation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class HandleAdminAppRequests
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
        $this->_attachUnapprovedSellersReg($response);
        // $responseData = array_merge($response->getData(true), [
        //     'extra_data' => 'Hello, From middlewatre!'
        // ]);
        // $response->setData($responseData);
        return $response;
    }

    /**
     * Attach data of vendor registrations awaiting approval
     * 
     * @param  \Illuminate\Http\Response  $response
     */
    private function _attachUnapprovedSellersReg(&$response)
    {
        if (!method_exists($response, 'getData')) {
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

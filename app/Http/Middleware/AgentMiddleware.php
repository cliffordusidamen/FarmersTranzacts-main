<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AgentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $approvalStatus
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $approvalStatus = null)
    {
        if (!authUserIsAgent()) {
            return redirect('/');
        }

        if (
            !empty($approvalStatus)
            && $request->user()->agent->approval_status != $approvalStatus
        ) {
            return redirect(route('dashboard'));
        }
        
        return $next($request);
    }
}

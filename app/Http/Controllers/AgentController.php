<?php

namespace App\Http\Controllers;

use App\Events\AgentApprovalStatusChangedEvent;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AgentController extends Controller
{

    /**
     * Change agent approval status
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, int $id)
    {
        $agent = Agent::findOrFail($id);
        $data = $request->only(['approval_status', 'reject_reason']);

        $validator = Validator::make($data, [
            'approval_status' => [
                'required',
                Rule::in(Agent::getApprovalStatuses()),
            ],

            'reject_reason' => 'sometimes|nullable|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'failed' => true,
            ]);
        }

        if ($agent->approval_status == $request->input('approval_status')) {
            return response()->json(compact(
                'agent'
            ));
        }

        $agent->approval_status = $request->input('approval_status');
        $agent->reject_reason = $agent->approval_status == Agent::STATUS_DISAPPROVED
            ? $request->input('reject_reason')
            : null;

        if ($agent->save()) {
            event(new AgentApprovalStatusChangedEvent(
                $agent, $agent->user
            ));
            return response()->json(compact(
                'agent'
            ));
        }

        return response()->json([
            'failed' => true,
        ]);
    }

}

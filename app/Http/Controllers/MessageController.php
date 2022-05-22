<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Designation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::withCount(['recipients'])->latest()->paginate(15);
        $messages->each(fn($message) => $message->append(['safe_content']));
        return response()->json(compact('messages'));
    }

    /**
     * Get data for displaying message creation form
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $designations = Designation::has('users')->get()->pluck('name', 'id');
        return response()->json(compact('designations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMessageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageRequest $request)
    {
        $saved = false;
        $message = null;
        DB::transaction(function () use ($request, &$saved, &$message) {
            $message = Message::create($request->all());
            $designationName = Designation::getDesignationName($request->designation_id);
            $usersList = User::designated($designationName)->pluck('id', 'id');
            $message->recipients()->attach($usersList);
            $saved = true;
        });

        if (!$saved) {
            return response()->json([
                'message' => "Failed to "
                    . ($request->is_draft ? 'save' : 'send')
                    . " message. Please try again."
            ], 500);
        }

        $data = [
            'message' => $message ? $message->toArray() : null,
            'success' => $saved,
        ];

        return response()->json(compact('data'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

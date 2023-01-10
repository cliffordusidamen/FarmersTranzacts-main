<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Designation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::withCount([
            'recipients',
            'sentRecipients',
        ])
        ->with([
            'creator',
            'editor',
        ])
        ->latest()
        ->paginate(15);

        $messages->each(fn($message) => $message->append([
            'safe_content',
            'is_sent',
        ]));
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
            $requestData = $request->all();
            $requestData['created_by'] =  auth()->user()->id;
            $message = Message::create($requestData);
            $message->createMessageUsers();

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
     * Preview an email
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function preview($id)
    {
        $message = Message::findOrFail((int) $id);

        return view('emails.admin_message', [
            'user' => $message->user,
            'adminMessage' => $message,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $message = Message::with([
            'designation',
            'recipients',
            'creator',
            'editor',
        ])
        ->withCount(['sentRecipients'])
        ->findOrFail((int) $id);
        
        $message->append('is_sent');
        $previewUrl = URL::temporarySignedRoute(
            'messages.preview', now()->addMinutes(5), ['id' => (int) $id]
        );

        return response()->json(compact('message', 'previewUrl'));
    }
    
    /**
     * Get message for edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $message = Message::draft()->findOrFail((int) $id);

        $designations = Designation::has('users')->get()->pluck('name', 'id');

        return response()->json(compact('message', 'designations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreMessageRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreMessageRequest $request, $id)
    {
        $message = Message::draft()->findOrFail((int) $id);
        $message->forceFill($request->validated());

        $saved = false;
        DB::transaction(function () use (&$saved, &$message) {
            $message->updated_by = auth()->user()->id;
            $message->save();
            $message->recipients()->detach();
            $message->createMessageUsers();
            $saved = true;
        });

        if (!$saved) {
            return response()->json([
                'save_failed' => true,
                'message' => 'Message not saved! Please, try again.'
            ]);
        }

        return response()->json(compact('message'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = Message::findOrFail((int) $id);

        $deleted = false;
        DB::transaction(function() use ($message, &$deleted) {
            $message->recipients()->detach();
            $deleted = $message->delete();
        });
        if (!$deleted) {
            throw new \Exception('Message not deleted!');
        }

        return response()->json();
    }
}

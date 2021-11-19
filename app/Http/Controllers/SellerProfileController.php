<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SellerProfileController extends Controller
{
    /**
     * Create instance of the controller
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'frontend']);
    }

    /**
     * Change user password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6|max:50|confirmed',
            'password_confirmation' => 'required|min:6|max:50',
        ]);
        $user = User::find($request->user()->id);
        $user->password = $request->input('password');
        if ($user->save()) {
            return response()->json(compact('user'));
        }

        throw new \Exception('Password was not changed! Please, try again.');
    }
}

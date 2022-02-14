<?php

namespace App\Http\Controllers;

use App\Actions\Auth\AuthenticateLogin;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;

class AuthFrontendController extends Controller
{

    /**
     * Authenticate the user and create personal access token
     * 
     * @throws \Illuminate\Validation\ValidationException
     * @return string
     */
    public function login()
    {
        $authenticate = new AuthenticateLogin();
        $user = $authenticate();
        if ($user) {
            return $user->createToken(request()->device_name)->plainTextToken;
        }

        throw ValidationException::withMessages([
            'email' => ['Invalid username or password!'],
        ]);
    }

    /**
     * Revoke access token
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json();
    }

    /**
     * Endpoint to send password reset link
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        User::where('designation_id', '!=', Designation::SHOPPER_ID)
            ->where('email', $request->email)
            ->firstOrFail();

        $status = Password::sendResetLink($request->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            throw new \Exception('Failed to send password reset link!');
        }

        return response()->json(['message' => 'Password reset link sent!']);
    }
}

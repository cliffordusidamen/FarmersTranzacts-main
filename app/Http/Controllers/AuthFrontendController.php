<?php

namespace App\Http\Controllers;

use App\Actions\Auth\AuthenticateLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
        // $currentToken = $request->user()->tokens->first(function ($token, $index) use ($request) {
        //     return Hash::check(
        //         $token->getKey().'|'. $request->bearerToken(),
        //         $token->token
        //     );
        // });

        // if ($currentToken) {
        //     $currentToken->delete();
        // }

        return response()->json();
    }
}

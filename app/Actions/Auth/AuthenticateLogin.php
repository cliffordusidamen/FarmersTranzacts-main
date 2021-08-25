<?php

namespace App\Actions\Auth;

use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthenticateLogin
{
    public function __invoke()
    {
        return $this->handle(request());
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User|bool
     */
    public function handle(Request $request)
    {
        $data = $request->only([
            'email',
            'password',
            'as_designation',
            'device_name',
        ]);

        $designationNames = [
            Designation::STAFF_NAME,
            Designation::SHOPPER_NAME,
            Designation::SELLER_NAME,
        ];
        $rules = [
            'email' => 'required|string|min:5',
            'password' => 'required|string|min:6',
            'as_designation' => ['required', 'string', Rule::in($designationNames)],
            'device_name' => 'required',
        ];
        $messages = [
            'email.min' => 'Invalid email address',
        ];
        Validator::make($data, $rules, $messages)->validate();

        $user = User::where('email', $request->email)
            ->orWhere('phone', $request->email)
            ->first();

        if (
            !$user
            || !Hash::check($request->password, $user->password)
        ) {
            return false;
        }
        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Account has been deactivated. Contact Admin'],
            ]);
        }

        $isMatchingDesignation = $user->is_super_admin
            || strtolower($user->designation->name) == $request->as_designation;

        $isStaffLogin = $request->input('as_designation') == Designation::STAFF_NAME;

        if (!$isStaffLogin && !$user->is_super_admin && !$isMatchingDesignation) {
            throw ValidationException::withMessages([
                'email' => ['Invalid username or password!'],
            ]);
        }

        return $user;
    }
}

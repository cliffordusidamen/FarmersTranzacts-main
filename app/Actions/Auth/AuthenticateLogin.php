<?php

namespace App\Actions\Auth;

use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            Designation::AGENT_NAME,
        ];
        $rules = [
            'email' => 'required|string|min:5',
            'password' => 'required|string|min:6',
            'as_designation' => ['sometimes', 'nullable', 'string', Rule::in($designationNames)],
            'device_name' => 'sometimes',
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

        $loginAsShopperOrAgent = empty($request->input('as_designation'))
            || $request->input('as_designation') == Designation::SHOPPER_NAME
            || $request->input('as_designation') == Designation::AGENT_NAME;

        if ($loginAsShopperOrAgent) {
            return $user;
        }

        $loginAsStaff = $request->input('as_designation') == Designation::STAFF_NAME;

        $role = $user->roles()->first();

        if ($loginAsStaff && !$user->is_super_admin && (!$role || !$role->is_active)){
            throw ValidationException::withMessages([
                'email' => ['Account deactivated!'],
            ]); 
        }

        $isMatchingDesignation = $user->is_super_admin
            || strtolower($user->designation->name) == $request->as_designation;

        if ((!$loginAsStaff && $user->is_super_admin) || !$isMatchingDesignation) {
            throw ValidationException::withMessages([
                'email' => ['Invalid username or password!'],
            ]);
        }

        return $user;
    }
}

<?php

namespace App\Actions\Auth;

use App\Models\Role;
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
        $data = $request->only(['email', 'password', 'as_role']);
        $roleNames = [
            Role::ADMIN_NAME,
            Role::SHOPPER_NAME,
            Role::VENDOR_NAME,
        ];
        $rules = [
            'email' => 'required|string|min:5',
            'password' => 'required|string|min:6',
            'as_role' => ['sometimes', 'string', Rule::in($roleNames)],
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

        $isMatchingRole = empty($request->as_role)
            || strtolower($user->role->name) == $request->as_role;

        if (!$user->is_super_admin && !$isMatchingRole) {
            throw ValidationException::withMessages([
                'email' => ['Access denied!'],
            ]);
        }

        return $user;
    }
}

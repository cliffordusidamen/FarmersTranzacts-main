<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Laravel\Jetstream\Jetstream;

class StoreVendorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'min:3', 'max:25'],
            'last_name' => ['required', 'string', 'min:3', 'max:25'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'numeric', 'min:11', 'max:15', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8', 'max:50'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',

            'store_name' => ['required', 'max:150', 'unique:vendors,name'],
            'store_phone' => ['required', 'min:11', 'max:16'],
            'store_address_line_1' => ['required', 'min:5', 'max:50'],
            'store_address_line_2' => ['nullable', 'min:5', 'max:50'],
            'store_city' => ['nullable', 'min:3', 'max:50'],
            'store_state' => ['required', 'exists:states,id'],
        ];
    }
}

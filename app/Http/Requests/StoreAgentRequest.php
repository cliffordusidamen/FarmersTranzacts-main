<?php

namespace App\Http\Requests;

use App\Models\Vendor;
use App\Rules\NigeriaMobile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;

class StoreAgentRequest extends FormRequest
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
            'phone' => ['nullable', 'string', 'max:15', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8', 'max:50'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',

            'business_name' => ['sometimes', 'nullable', 'max:150', 'unique:agents,business_name'],
            'spending_volume_id' => ['required', 'exists:agent_spending_volumes,id'],
            'store_address_line_1' => ['required', 'min:5', 'max:50'],
            'store_address_line_2' => ['nullable', 'min:5', 'max:50'],
            'store_city' => ['nullable', 'min:3', 'max:50'],
            'store_state' => ['required', 'exists:states,id'],

            'product_categories' => ['array', 'min:1'],
        ];
    }
}

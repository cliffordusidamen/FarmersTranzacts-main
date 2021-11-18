<?php

namespace App\Http\Requests;

use App\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
            'phone' => ['nullable', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8', 'max:50'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',

            'shop_name' => ['required', 'max:150', 'unique:vendors,shop_name'],
            'business_name' => ['required', 'max:150', 'unique:vendors,business_name'],
            'entity_country_id' => ['nullable', 'exists:countries,id'],
            'tin' => ['min:5', 'max:150', 'unique:vendors,tin'],
            'ownership_type' => ['required',Rule::in(Vendor::getOwnershipTypes())],
            'address_line_1' => ['required', 'min:5', 'max:50'],
            'address_line_2' => ['nullable', 'min:5', 'max:50'],
            'postal_code' => ['nullable', 'min:3', 'max:10'],
            'city' => ['nullable', 'min:3', 'max:50'],
            'state_id' => ['required', 'exists:states,id'],
            'shipping_country_id' => ['nullable', 'exists:countries,id'],
            'store_phone' => ['nullable', 'min:11', 'max:16'],
            'product_categories' => ['array', 'min:1'],
        ];
    }
}

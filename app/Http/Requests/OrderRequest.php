<?php

namespace App\Http\Requests;

use App\Models\Address;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $addressRules = Address::validationRules();
        unset($addressRules['state_id']);

        return array_merge($addressRules, [
            'order_notes' => ['sometimes', 'nullable', 'string', 'max:200']
        ]);
    }
}

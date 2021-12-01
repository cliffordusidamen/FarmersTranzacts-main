<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpendingVolumeRequest extends FormRequest
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
        return [
            'minimum_amount' => 'required|numeric|min:0|max:999999999',
            'maximum_amount' => 'sometimes|nullable|numeric|min:0|max:999999999',
            'commission_percentage' => 'required|numeric|min:0.1|max:99|unique:agent_spending_volumes',
        ];
    }
}

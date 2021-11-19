<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactUsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/[a-zA-Z\s]/i',
            ],
            'email' => 'nullable|requiredWithout:phone|email',
            'phone' => 'nullable|requiredWithout:email|min:11|max:16',
            'subject' => 'nullable|max:200',
            'message' => 'required|string|max:1000',
        ];
    }

    /**
     * Sets custom messages for validation
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'min' => ':attribute is too short'
        ];
    }
}
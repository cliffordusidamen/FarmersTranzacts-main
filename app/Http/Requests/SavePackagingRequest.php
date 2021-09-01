<?php

namespace App\Http\Requests;

use App\Models\VendorPackaging;
use Illuminate\Foundation\Http\FormRequest;

class SavePackagingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return VendorPackaging::validationRules();
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class SaveProductRequest extends FormRequest
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
        return Product::validationRules();
    }

    /**
     * Attribute names
     * 
     * @return array
     */
    public function attributes()
    {
        return [
            'category_id' => 'category',
            'subcategory_id' => 'subcategory',
            'packaging_id' => 'packaging',
        ];
    }
}

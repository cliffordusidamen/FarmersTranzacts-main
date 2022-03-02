<?php

namespace App\Http\Requests;

use App\Models\FarmSeason;
use Illuminate\Foundation\Http\FormRequest;

class StoreFarmSeasonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return authUserIsAgent();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return FarmSeason::defaultValidationRules();
    }
}

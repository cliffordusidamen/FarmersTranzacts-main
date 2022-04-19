<?php

namespace App\Http\Requests;

use App\Models\FarmSeason;
use App\Models\ReferralRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StoreReferralRequest extends FormRequest
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
        Log::info(request()->all());
        $referralRequestValidationRules = ReferralRequest::defaultValidationRules();

        $farmSeasonValidationRules = FarmSeason::defaultValidationRules();
        $rules = array_merge($referralRequestValidationRules, $farmSeasonValidationRules);

        $rules['farm_season_inputs'] = 'array|required|min:1|max:20';
        $rules['farm_season_produces'] = 'array|required|min:1|max:20';

        return $rules;
    }
}

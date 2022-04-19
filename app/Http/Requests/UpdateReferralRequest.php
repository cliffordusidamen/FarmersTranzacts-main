<?php

namespace App\Http\Requests;

use App\Models\FarmSeason;
use App\Models\ReferralRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UpdateReferralRequest extends FormRequest
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
        $rules = ReferralRequest::getFullValidationRules(
            Route::current()->getParameter('id')
        );

        return $rules;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesCommission extends Model
{
    use HasFactory;

    /**
     * Guarded attributes
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Default validation rules
     * 
     * @return array
     */
    public static function validationRules(): array
    {

        $rules = [
            'minimum_amount' => 'required|numeric|min:0|max:999999999',
            'maximum_amount' => 'sometimes|nullable|numeric|min:0|max:999999999',
            'percentage' => [
                'required',
                'numeric',
                'min:0.1',
                'max:99',
            ],
        ];

        return $rules;
    }

    /**
     * CHeck if spending volume exists for specified amount
     * 
     * @param  float  $amount
     * @return SalesCommission|null
     */
    public static function checkAmount(float $amount): ?SalesCommission
    {
        $agentSpendingVolume = SalesCommission::where('minimum_amount', '<=', $amount)
            ->where(function(Builder $q) use ($amount) {
                $q->where('maximum_amount', '=', NULL)
                    ->orWhere(function(Builder $q) use ($amount) {
                        return $q->where('maximum_amount', '!=', NULL)
                            ->where('maximum_amount', '>=', $amount);
                    });
                })
            ->first();

        return $agentSpendingVolume ?? null;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

class AgentSpendingVolume extends Model
{
    use HasFactory;

    /**
     * Guarded attributes
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Relationship: has many agents
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class, 'spending_volume_id');
    }

    /**
     * CHeck if spending volume exists for specified amount
     * 
     * @param  float  $amount
     * @return AgentSpendingVolume|null
     */
    public static function checkAmount(float $amount): ?AgentSpendingVolume
    {
        $agentSpendingVolume = AgentSpendingVolume::where('minimum_amount', '<=', $amount)
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

    /**
     * Default validation rules
     * 
     * @param  int|null  $id
     * @return array
     */
    public static function validationRules(?int $id = null): array
    {
        
        $commissionUniqueRule = Rule::unique('agent_spending_volumes', 'commission_percentage');

        if ($id) {
            $commissionUniqueRule->ignore($id);
        }

        $rules = [
            'minimum_amount' => 'required|numeric|min:0|max:999999999',
            'maximum_amount' => 'sometimes|nullable|numeric|min:0|max:999999999',
            'commission_percentage' => [
                'required',
                'numeric',
                'min:0.1',
                'max:99',
                $commissionUniqueRule,
            ],
        ];

        return $rules;
    }
}

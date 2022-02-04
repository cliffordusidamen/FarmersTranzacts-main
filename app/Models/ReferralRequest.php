<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

class ReferralRequest extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPORVED = 'approved';
    const STATUS_DISAPPROVED = 'disapproved';

    /**
     * Guarded fields
     * 
     * @var array
     */
    protected $guarded = [];


    /**
     * Relationship: belongs to user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Relationship: belongs to state
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    
    /**
     * Get list of statuses
     *
     * @param  bool $asKeys
     * @return array
     */
    public static function getStatuses(bool $asKeys = false): array
    {
        $statuses = [
            self::STATUS_PENDING => ucwords(self::STATUS_PENDING),
            self::STATUS_APPORVED => ucwords(self::STATUS_APPORVED),
            self::STATUS_DISAPPROVED => ucwords(self::STATUS_DISAPPROVED),
        ];

        return $asKeys ? $statuses : array_keys($statuses);
    }

    /**
     * Default validation rules
     * 
     * @return array
     */
    public static function defaultValidationRules(): array
    {
        $rules = [
            'first_name' => "required|min:3|max:25",
            'last_name' => "required|min:3|max:25",
            'email' => "sometimes|nullable|email",
            'phone' => "required|min:10|max:16",
            'age' => "sometimes|nullable|numeric|min:25|max:120",
            'business_name' => 'sometimes|nullable|min:3',
            'address_line_1' => 'required|min:3|max:255',
            'address_line_2' => 'sometimes|nullable|max:255',
            'city' => 'sometimes|nullable|max:50',
            'state_id' => 'required|exists:states,id',
            'crops_grown' => [
                'nullable',
                Rule::requiredIf(fn() => empty(request('animals_reared')))
            ],
            'animals_reared' => [
                'nullable',
                Rule::requiredIf(fn() => empty(request('crops_grown')))
            ],
            'farm_size' => 'required|min:3|max:25',
            'seed_demand' => 'sometimes|nullable|max:500',
            'animal_feed_demand' => 'sometimes|nullable|max:500',
            'fertilizer_demand' => 'sometimes|nullable|max:500',
            'agro_chemical_demand' => 'sometimes|nullable|max:500',
            'number_of_workers' => 'required|min:1|max:9999999',
        ];

        return $rules;
    }
}

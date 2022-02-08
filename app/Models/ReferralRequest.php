<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
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
     * Get number associated to a status
     *
     * @param  string  $status
     * @return int|null
     */
    public static function getStatusNumber(string $status): ?int
    {
        $statuses = [
            self::STATUS_PENDING => 0,
            self::STATUS_APPORVED => 1,
            self::STATUS_DISAPPROVED => -1,
        ];

        return $statuses[$status] ?? null;
    }

    /**
     * Default validation rules
     * 
     * @param  int|null  $id
     * @return array
     */
    public static function defaultValidationRules(?int $id = null): array
    {
        $uniqueEmailRule = Rule::unique('referral_requests', 'email');
        $uniquePhoneRule = Rule::unique('referral_requests', 'phone');

        if ($id) {
            $uniqueEmailRule->ignore($id);
            $uniquePhoneRule->ignore($id);
        }

        $rules = [
            'first_name' => "required|min:3|max:25",
            'last_name' => "required|min:3|max:25",
            'email' => ["sometimes", "nullable", "email", $uniqueEmailRule],
            'phone' => ["required", "min:10", "max:16", $uniquePhoneRule],
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

    /**
     * Get full name
     * 
     * @return string
     */
    public function getFullNameAttribute()
    {
        return ucwords(strtolower($this->first_name . ' ' . $this->last_name));
    }

    /**
     * Determine if the referral is editable to the user
     * 
     * @return bool
     */
    public function getIsEditableAttribute(): bool
    {
        return $this->approval_status === self::STATUS_PENDING;
    }

    /**
     * Get status number attribute
     * 
     * @return int
     */
    public function getStatusNumberAttribute(): int
    {
        return self::getStatusNumber($this->approval_status);
    }

    /**
     * Filter by editable only
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeEditable($query)
    {
        $query->where('approval_status', self::STATUS_PENDING);
    }

    /**
     * Scope/Search filter by state_id
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|null  $stateId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByState(
        Builder $query,
        ?int $stateId = null
    ): Builder {
        if ($stateId) {
            $query->where('state_id', $stateId);
        }

        return $query;
    }

    /**
     * Scope/Search filter by namespace
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $searchString
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchName(
        Builder $query,
        ?string $searchString = null
    ): Builder {
        if (!empty($searchString)) {
            $query->where(function(Builder $q) use ($searchString) {
                $q->where(DB::raw('CONCAT(last_name, " ", first_name, " ", middle_name)'), 'LIKE', "%{$searchString}%")
                    ->orWhere(DB::raw('CONCAT(last_name, " ", middle_name, " ", first_name)'), 'LIKE', "%{$searchString}%")
                    ->orWhere(DB::raw('CONCAT(last_name, " ", first_name)'), 'LIKE', "%{$searchString}%")
                    ->orWhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', "%{$searchString}%");
            });
        }

        return $query;
    }
}

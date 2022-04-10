<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Validation\Rule;

class Farm extends Model
{
    use HasFactory;

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
     * Relationship: Has one referral request
     *
     * @return HasOne
     */
    public function referralRequest(): HasOne
    {
        return $this->hasOne(ReferralRequest::class);
    }

    /**
     * Relationship: Has many farm seasons
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seasons(): HasMany
    {
        return $this->hasMany(FarmSeason::class);
    }

    /**
     * Custom attribute: last_season
     * 
     * @return \App\Models\FarmSeason|null
     */
    public function getLastSeasonAttribute(): ?FarmSeason
    {
        return $this->seasons()->orderByDesc('year')->first();
    }

    /**
     * Check if farm has season for the indicated year and season
     * 
     * @param  int  $year
     * @param  string  $season
     * @return bool
     */
    public function alreadyHasSeason(int $year, string $season): bool
    {
        return $this->seasons()->where('year', $year)->where('season', $season)->exists();
    }

    
    /**
     * Default validation rules
     * 
     * @return array
     */
    public static function defaultValidationRules(): array
    {

        $rules = [
            'business_name' => 'sometimes|nullable|min:3',
            'state_id' => 'required|exists:states,id',
            'local_government_area_id' => ['sometimes', 'nullable', 'exists:local_government_areas,id'],
            'address_line_1' => 'required|min:3|max:255',
            'address_line_2' => 'sometimes|nullable|max:255',
            'ward' => ['sometimes', 'nullable', 'max:50'],
            'postal_code' => ['sometimes', 'nullable', 'max:15'],
            'city' => 'sometimes|nullable|max:50',
        ];


        return $rules;
    }

}

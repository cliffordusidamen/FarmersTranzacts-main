<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Farm extends Model
{
    use HasFactory;

    /**
     * Guarded fields
     * 
     * @var array
     */
    protected $guarded;


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

}

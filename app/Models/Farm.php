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
    public function farmSeasons(): HasMany
    {
        return $this->hasMany(FarmSeason::class);
    }
}

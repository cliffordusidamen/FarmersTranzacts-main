<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmSeasonInput extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'farm_season_id',
        'name',
        'unit',
        'quantity',
        'cost_per_unit',
    ];

    /**
     * Relationship: belongs to farm season
     *
     * @return BelongsTo
     */
    public function farmSeason(): BelongsTo
    {
        return $this->belongsTo(FarmSeason::class);
    }
}

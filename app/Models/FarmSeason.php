<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmSeason extends Model
{
    use HasFactory;

    const SEASON_RAINY = 'rainy';
    const SEASON_DRY = 'dry';

    /**
     * Guarded fields
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Relationship: Belongs to farm
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    /**
     * Get list of seasons
     *
     * @param  bool $asKeys
     * @return array
     */
    public static function getSeasons(bool $asKeys = false): array
    {
        $seasons = [
            self::SEASON_DRY => ucwords(self::SEASON_DRY),
            self::SEASON_RAINY => ucwords(self::SEASON_RAINY),
        ];

        return $asKeys ? $seasons : array_keys($seasons);
    }
}

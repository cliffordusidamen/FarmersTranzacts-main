<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

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

    
    /**
     * Default validation rules
     * 
     * @param  int|null  $id
     * @return array
     */
    public static function defaultValidationRules(?int $id = null): array
    {

        $rules = [
            'farm_size' => 'required|min:3|max:25',
            'workers_pay' => 'sometimes|nullable|max:500',
            'number_of_workers' => 'required|min:1|max:9999999',
            'year' => [
                'required',
                Rule::in([
                    date('Y') - 1,
                    date('Y')
                ]),
            ],

            'season' => [
                'required',
                Rule::in(FarmSeason::getSeasons()),
            ],

            'season_notes' => 'sometimes|nullable|max:5000',
        ];

        return $rules;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeopoliticalZone extends Model
{
    use HasFactory;

    const NORTH_CENTRAL = 1;
    const NORTH_EAST = 2;
    const NORTH_WEST = 3;
    const SOUTH_EAST = 4;
    const SOUTH_SOUTH = 5;
    const SOUTH_WEST = 6;

    /**
     * Relationship: has many states
     * 
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    /**
     * Get list of zones
     * 
     * @param  bool  $asKeys
     * @return array
     */
    public static function getList(bool $asKeys = false): array
    {
        $zones = [
            self::NORTH_CENTRAL => 'North-central',
            self::NORTH_EAST => 'North-east',
            self::NORTH_WEST => 'North-west',
            self::SOUTH_EAST => 'South-east',
            self::SOUTH_SOUTH => 'South-south',
            self::SOUTH_WEST => 'South-west',
        ];

        if (!$asKeys) {
            return array_keys($zones);
        }

        return $zones;
    }
}

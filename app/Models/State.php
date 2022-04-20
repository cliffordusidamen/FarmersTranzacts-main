<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory;

    /**
     * Relationship: Belongs to geopolitical zone
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(GeopoliticalZone::class, 'geopolitical_zone_id');
    }

    /**
     * Relationship: Has many local government areas
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function localGovtAreas(): HasMany
    {
        return $this->hasMany(LocalGovernmentArea::class, 'state_id');
    }

}

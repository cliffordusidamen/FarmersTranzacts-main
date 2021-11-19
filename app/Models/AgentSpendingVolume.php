<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgentSpendingVolume extends Model
{
    use HasFactory;

    /**
     * Guarded attributes
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Relationship: has many agents
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class);
    }
}

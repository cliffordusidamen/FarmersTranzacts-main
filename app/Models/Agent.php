<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Agent extends Model
{
    use HasFactory;

    /**
     * Guarded attributes
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Relationships: belongs to spending volume
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function spendingVolume(): BelongsTo
    {
        return $this->belongsTo(AgentSpendingVolume::class);
    }

    /**
     * Relatioship: belongs to many product categories
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function productCategories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class);
    }
}

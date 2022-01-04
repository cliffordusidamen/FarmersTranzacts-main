<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpPage extends Model
{
    use HasFactory;

    /**
     * Scope a query to only include published pages.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }
}

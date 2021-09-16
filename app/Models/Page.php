<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    const ABOUT = 'about';
    const TERMS = 'shopper_terms';
    const PRIVACY_POLICY = 'privary_policy';

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'title',
        'content',
    ];

    /**
     * Get the by name
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $pageName
     * @return  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeByName(Builder $query, string $pageName): Builder
    {
        return $query->where('name', $pageName);
    }

    /**
     * Get the about page
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeAbout(Builder $query): Builder
    {
        return $this->byName(self::ABOUT);
    }

}

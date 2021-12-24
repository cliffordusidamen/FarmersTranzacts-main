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
    
    const AGENT_TERMS = 'agent_terms';

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'slug',
        'title',
        'content',
    ];

    /**
     * Get the by slug
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $pageSlug
     * @return  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeBySlug(Builder $query, string $pageSlug): Builder
    {
        return $query->where('slug', $pageSlug);
    }

    /**
     * Get the about page
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeAbout(Builder $query): Builder
    {
        return $this->bySlug(self::ABOUT);
    }

}

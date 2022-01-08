<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class HelpPage extends Model
{
    use HasFactory;

    /**
     * Guarded fields
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Scope a query to only include published pages.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    /**
     * Default validation rules
     * 
     * @param  int|null  $id
     * @return array
     */
    public static function validationRules(?int $id = null): array
    {
        $slugUniqueRule = Rule::unique('help_pages', 'slug');
        
        if ($id) {
            $slugUniqueRule->ignore($id);
        }

        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                $slugUniqueRule,
            ],
            'description' => 'required|string|max:255',
            'content' => 'required|string|max:65000',
            'published' => 'boolean',
        ];
    }
}

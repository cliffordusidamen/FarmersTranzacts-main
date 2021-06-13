<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    
    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'store_id',
        'category_id',
        'subcategory_id',
        'name',
        'slug',
        'description',
        'price',
        'old_price',
        'in_stock',
    ];

    /**
     * Relationship: Belongs to product category
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Relationship: Belongs to product subcategory
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(ProductSubcategory::class, 'subcategory_id');
    }

    /**
     * Relationship: Belongs to store
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     * Relationship: Has many to product images
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    /**
     * Mutator: create unique slug, based on name
     * 
     * @param  string  $value
     */
    public function setNameAttribute($value)
    {
        if (
            empty($this->id)
            && !empty(trim($value))
            && empty($this->attributes['slug'])
        ) {
            $this->attributes['slug'] = Str::slug($value);
        }

        $this->attributes['name'] = $value;
    }

    /**
     * Validation rules
     * 
     * @param  int|null  $id
     * @return array
     */
    public static function validationRules(?int $id = null): array
    {
        $uniqueRule = 'unique:stores,name' . (!is_null($id) ? ",{$id}" : '');

        return [
            'name' => ['required', 'min:3', 'max:255', $uniqueRule, ],
            'description' => ['nullable', 'min:3', 'max:255', ],
        ];
    }
}

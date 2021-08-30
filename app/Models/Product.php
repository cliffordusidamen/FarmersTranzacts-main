<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'awaiting approval';
    const STATUS_APPROVED = 'approved';
    const STATUS_DISAPPROVED = 'disapproved';
    
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
        'disapproval_reason',
        'approval_status',
        'submitted_at',
        'approved_at',
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

    /**
     * Get list of statuses
     *
     * @param  bool $asKeys
     * @return array
     */
    public static function getApprovalStatuses(bool $asKeys = false): array
    {
        $types = [
            self::STATUS_PENDING => ucwords(self::STATUS_PENDING),
            self::STATUS_APPROVED => ucwords(self::STATUS_APPROVED),
            self::STATUS_DISAPPROVED => ucwords(self::STATUS_DISAPPROVED),
        ];

        return $asKeys ? $types : array_keys($types);
    }

    /**
     * filter by submitted
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSubmitted(Builder $query): Builder
    {
        return $query->where('submitted_at', '!=', NULL)
            ->where('approval_status', Product::STATUS_PENDING);
    }
}

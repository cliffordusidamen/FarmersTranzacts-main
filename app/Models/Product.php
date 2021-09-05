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
        'vendor_id',
        'category_id',
        'subcategory_id',
        'packaging_id',
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
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
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
     * Relationship: belongs to product packaging
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function packaging(): BelongsTo
    {
        return $this->belongsTo(VendorPackaging::class, 'packaging_id');
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
        return [
            'name' => 'required|string|min:5|max:100',
            'description' => 'required|min:25|max:1000',
            'price' => 'required|numeric|max:99999999',
            'packaging_id' => 'required|exists:vendor_packagings,id',
            'category_id' => 'required|exists:product_categories,id',
            'subcategory_id' => 'sometimes|nullable|exists:product_subcategories,id',
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
            ->whereIn('approval_status', [
                Product::STATUS_PENDING,
                Product::STATUS_APPROVED,
            ]);
    }

    /**
     * filter by status(awaiting approval only)
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAwaitingApproval(Builder $query): Builder
    {
        return $query->where('approval_status', self::STATUS_PENDING);
    }

    /**
     * filter by available for sale:
     *     approved
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailableForSale(Builder $query): Builder
    {
        return $query->where('approval_status', self::STATUS_APPROVED);
    }

    /**
     * filter: in stock or not
     *     approved
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|null $inStock
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInStock(Builder $query, ?bool $inStock = null): Builder
    {
        $query = $query->where('approval_status', self::STATUS_APPROVED);

        if (!is_null($inStock)) {
            $query->where(
                'in_stock',
                !$inStock ? '==' : '!=',
                0
            );
        }

        return $query;
    }
}

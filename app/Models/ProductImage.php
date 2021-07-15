<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'product_id',
        'path',
        'is_featured',
    ];

    /**
     * Relationship: Belongs to product
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

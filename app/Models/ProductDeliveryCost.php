<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDeliveryCost extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'product_id',
        'minimum_quantity',
        'maximum_quantity',
        'north_central',
        'north_east_cost',
        'north_east_delivery_days',
        'north_west_cost',
        'north_west_delivery_days',
        'south_east_cost',
        'south_east_delivery_days',
        'south_west_cost',
        'south_west_delivery_days',
        'south_south_cost',
        'south_south_delivery_days',
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

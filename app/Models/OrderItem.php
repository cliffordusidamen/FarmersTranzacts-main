<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_amount',
        'total_amount',
    ];

    /**
     * Relationship: belongs to order
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

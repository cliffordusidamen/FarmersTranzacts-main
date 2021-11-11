<?php

namespace App\Models;

use App\Models\Traits\QueriesDateField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;
    use QueriesDateField;

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'order_id',
        'vendor_order_id',
        'product_id',
        'quantity',
        'unit_amount',
        'total_amount',
        'delivery_amount',
        'delivery_days',
        'grand_total_amount',
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

    /**
     * Relationship: belongs to vendor order
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendorOrder(): BelongsTo
    {
        return $this->belongsTo(VendorOrder::class, 'vendor_order_id');
    }

    /**
     * Relationship: Belongs to a product
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Order items that belong to vendor orders, that have been delivered
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfDeliveredVendorOrders(Builder $query): Builder
    {
        return $query->where(function($q) {
            $q->whereIn('vendor_order_id', VendorOrder::delivered()->pluck('id'));
        });
    }
    /**
     * Created within the current month
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeThisMonth(Builder $query): Builder
    {
        return $this->_thisMonth($query, 'created_at');
    }
}

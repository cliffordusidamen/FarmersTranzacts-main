<?php

namespace App\Models;

use App\Models\Traits\Deliverable;
use App\Models\Traits\QueriesDateField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorOrder extends Model
{
    use HasFactory;
    use Deliverable;
    use QueriesDateField;

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'reference_number',
        'user_id',
        'order_id',
        'vendor_id',
        'items_amount',
        'delivery_amount',
        'delivery_days',
        'total_amount',
        'delivery_status_id',
    ];
    
    /**
     * Relationship: Belongs to a vendor
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    
    /**
     * Relationship: Belongs to a user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: Belongs to a order
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Relatioship: Has many order items
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'vendor_order_id');
    }

    /**
     * Created with a particular date range
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null $dateFrom
     * @param  string|null $dateTo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDated(Builder $query, ?string $dateFrom = null, ?string $dateTo = null): Builder
    {
        return $this->_dated(
            $query,
            'created_at',
            $dateFrom,
            $dateTo
        );
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

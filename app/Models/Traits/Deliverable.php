<?php

namespace App\Models\Traits;

use App\Models\DeliveryStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Deliverable
{

    /**
     * Relationship: Belongs to delivery status
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deliveryStatus(): BelongsTo
    {
        return $this->belongsTo(DeliveryStatus::class, 'delivery_status_id');
    }

    /**
     * Not cancelled
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotCancelled(Builder $query): Builder
    {
        return $query->where('delivery_status_id', '!=', DeliveryStatus::CANCELED_ID);
    }

    /**
     * Not cancelled, nor returned
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotCancelledOrReturned(Builder $query): Builder
    {
        return $query->where('delivery_status_id', '!=', DeliveryStatus::CANCELED_ID)
            ->where('delivery_status_id', '!=', DeliveryStatus::RETURNED_ID);
    }

    /**
     * Filter by wheather delivered or not
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool $delivered
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDelivered(Builder $query, bool $delivered = true): Builder
    {
        $comparisonOperator = $delivered ? '=' : '!=';
        return $query->where(
            'delivery_status_id',
            $comparisonOperator,
            DeliveryStatus::DELIVERED_ID
        );
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
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        return $query;
    }

    /**
     * Created within the current month
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeThisMonth(Builder $query): Builder
    {
        return $this->scopeDated(
            $query,
            now()->startOfMonth()->format('Y-m-d'),
            now()->endOfMonth()->format('Y-m-d')
        );
    }
}
<?php

namespace App\Models\Traits;

use App\Models\AppSetting;
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
     * Pending delivery status
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('delivery_status_id', DeliveryStatus::PENDING_ID);
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

    /**
     * Get if current entity is deliverable
     * 
     * @return bool
     */
    public function getDeliverableAttribute(): bool
    {
        return DeliveryStatus::isDeliverable($this->delivery_status_id);
    }

    /**
     * Determine if current entity is transitable
     * 
     * @return bool
     */
    public function getTransitableAttribute(): bool
    {
        return DeliveryStatus::isTransitable($this->delivery_status_id);
    }

    /**
     * Determine if current entity is cancellable
     * 
     * @return bool
     */
    public function getCancellableAttribute(): bool
    {
        if (
            $this->delivery_status_id != DeliveryStatus::PENDING_ID
        ) {
            return false;
        }

        $maxCancellationHours = (int) AppSetting::getSetting(AppSetting::CANCEL_ORDER_HOURS);
        $cancellationTs = (int) carbon_ng($this->created_at)
            ->addHours($maxCancellationHours)
            ->format('U');

        $nowTs = (int) carbon_ng()->format('U');

        return  $cancellationTs >= $nowTs;
    }

    /**
     * Filter order than are not cancellable,
     * based on the max number hours set in app_settings table(by admin)
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $elapsed
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancellationPeriodElapsed(
        Builder $query,
        bool $elapsed = true
    ): Builder {
        $maxCancellationHours = (int) AppSetting::getSetting(AppSetting::CANCEL_ORDER_HOURS);
        $now = now()->format('Y-m-d H:i:s');
        $comparisonSymbol = $elapsed ? '>=' : '<';
        $rawDateCheck = "? {$comparisonSymbol} DATE_ADD(created_at, INTERVAL {$maxCancellationHours} HOUR)";
        return $query->whereRaw($rawDateCheck, [$now]);
    }
}
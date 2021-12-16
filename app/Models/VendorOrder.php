<?php

namespace App\Models;

use App\Models\Traits\Deliverable;
use App\Models\Traits\QueriesDateField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class VendorOrder extends Model
{
    use HasFactory;
    use Deliverable;
    use QueriesDateField;

    /**
     * Guarded fields
     * 
     * @var array
     */
    protected $guarded = [];
    
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

    /**
     * Get orders for a particular vendor
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $vendorId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForVendor(Builder $query, int $vendorId): Builder
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Filter completed orders
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted(Builder $query): Builder {
        return $query->whereNotIn('delivery_status_id', [
            DeliveryStatus::PENDING_ID,
            DeliveryStatus::IN_TRANSIT_ID,
        ]);
    }

    /**
     * Get the sales for the last 8 months
     * 
     * @param  int  $numberOfMonths
     * @param  int|null  $vendorId
     * @return array
     */
    public static function getSalesTrend(
        int $numberOfMonths = 8,
        ?int $vendorId = null
    ): array {
        $numberOfMonthsToUse = $numberOfMonths < 6 || $numberOfMonths > 12
            ? 6 : $numberOfMonths;

        $monthlyPurchases = [];

        for ($i = $numberOfMonthsToUse - 1; $i >= 0; $i--) {
            $month = date('M Y', strtotime("-$i month"));
            $query = VendorOrder::delivered();

            if ($vendorId) {
                $query->where('vendor_id', $vendorId);
            }

            $monthlyPurchases[$month] = $query
                ->whereMonth('created_at', '=', date('m', strtotime("-$i month")))
                ->whereYear('created_at', '=', date('Y', strtotime("-$i month")))
                ->addSelect(DB::raw('SUM(total_amount) as month_total_amount'))
                ->addSelect(DB::raw('COUNT(id) as total_orders'))
                ->first();
        }

        return $monthlyPurchases;
    }

    /**
     * Get the purchases made by a particular for recent months
     * 
     * @param  int  $numberOfMonths
     * @param  int  $userId
     * @return array
     */
    public static function getUserMonthlyPurchases(
        int $numberOfMonths = 8,
        int $userId
    ): array {
        $numberOfMonthsToUse = $numberOfMonths < 6 || $numberOfMonths > 12
            ? 6 : $numberOfMonths;

        $monthlyPurchases = [];

        for ($i = $numberOfMonthsToUse - 1; $i >= 0; $i--) {
            $month = date('M Y', strtotime("-$i month"));
            $query = VendorOrder::delivered();

            if ($userId) {
                $query->where('user_id', $userId);
            }

            $monthlyPurchases[$month] = $query
                ->whereMonth('created_at', '=', date('m', strtotime("-$i month")))
                ->whereYear('created_at', '=', date('Y', strtotime("-$i month")))
                ->addSelect(DB::raw('SUM(total_amount) as month_total_amount'))
                ->addSelect(DB::raw('COUNT(id) as total_orders'))
                ->first();
        }

        $currMonthSpending = $monthlyPurchases[carbon_ng()->format('M Y')];
        $prevMonthSpending = $monthlyPurchases[
            carbon_ng()->subtract('1 month')->format('M Y')
        ];
        $spendingDifference =
            $currMonthSpending->month_total_amount - $prevMonthSpending->month_total_amount;

        return compact(
            'monthlyPurchases',
            'currMonthSpending',
            'prevMonthSpending',
            'spendingDifference'
        );
    }

    /**
     * Custom attribute to determine if the customer can leave review
     * 
     * @return bool
     */
    public function getCanLeaveReviewAttribute(): bool
    {
        if (
            !empty($this->customer_reviewed_at)
            || empty($this->delivery_date)
            || $this->delivery_status_id !== DeliveryStatus::DELIVERED_ID
        ) {
            return false;
        }

        if (
            carbon_ng($this->delivery_date)->diffInDays(carbon_ng()) <= 7
        ) {
            return true;
        }

        return false;
    }

}

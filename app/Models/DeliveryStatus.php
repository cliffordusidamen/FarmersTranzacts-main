<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryStatus extends Model
{
    use HasFactory;

    const  PENDING_ID = 1;
    const  IN_TRANSIT_ID = 2;
    const  DELIVERED_ID = 3;
    const  RETURNED_ID = 4;
    const  CANCELED_ID = 5;
    const  COMPLETED_ID = 6;

    const  PENDING = 'pending';
    const  IN_TRANSIT = 'in transit';
    const  DELIVERED = 'delivered';
    const  RETURNED = 'returned';
    const  CANCELLED = 'cancelled';
    const  COMPLETED = 'completed';

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relationship: has many orders
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'shipping_status_id');
    }

    /**
     * Get list of statuses
     *
     * @param  bool $asKeys
     * @return array
     */
    public static function getList(bool $asKeys = false): array
    {
        $statuses = [
            self::PENDING_ID => ucwords(self::PENDING),
            self::IN_TRANSIT_ID => ucwords(self::IN_TRANSIT),
            self::DELIVERED_ID => ucwords(self::DELIVERED),
            self::RETURNED_ID => ucwords(self::RETURNED),
            self::CANCELED_ID => ucwords(self::CANCELLED),
            self::COMPLETED_ID => ucwords(self::COMPLETED),
        ];

        return $asKeys ? $statuses : array_keys($statuses);
    }

    /**
     * Get the name associated with a status id
     * 
     * @param  int  $deliveryStatusId
     * @throws \Exception
     * @return string
     */
    public static function getStatusName(int $deliveryStatusId): string
    {
        $statuses = self::getList(true);
        if (!isset($statuses[$deliveryStatusId])) {
            throw new \Exception("Invalid delivery status id: '{$deliveryStatusId}'!");
        }

        return $statuses[$deliveryStatusId];
    }

    /**
     * CHeck if the specified status id is not cancelled or returned
     * 
     * @param  int  $deliveryStatusId
     * @return bool
     */
    public static function isNotCancelledOrReturned(int $deliveryStatusId): bool
    {
        return $deliveryStatusId !== self::CANCELED_ID && $deliveryStatusId !== self::RETURNED_ID;
    }

    /**
     * Determine if the specified status id is deliverable
     * 
     * @param  int  $deliveryStatusId
     * @return bool
     */
    public static function isDeliverable(int $deliveryStatusId): bool {
        return $deliveryStatusId != self::DELIVERED_ID
            && (
                $deliveryStatusId == self::PENDING_ID
                || $deliveryStatusId == self::IN_TRANSIT_ID
            );
    }

    /**
     * Determine if the specified status id can be set as in transit
     * 
     * @param  int  $deliveryStatusId
     * @return bool
     */
    public static function isTransitable(int $deliveryStatusId): bool {
        return self::isDeliverable($deliveryStatusId)
            && $deliveryStatusId !== self::IN_TRANSIT_ID;
    }
}

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

    const  PENDING = 'pending';
    const  IN_TRANSIT = 'in transit';
    const  DELIVERED = 'delivered';
    const  RETURNED = 'returned';
    const  CANCELLED = 'cancelled';

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
        ];

        return $asKeys ? $statuses : array_keys($statuses);
    }
}

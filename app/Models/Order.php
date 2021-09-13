<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',
        'amount',
        'order_notes',
    ];

    /**
     * Relatioship: Has many order items
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Relationship: Payment(s) for order
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    /**
     * Generate payment reference number
     * 
     * @param  \App\Models\Order  $order
     * @return string
     */
    public static function generatePaymentReference(Order $order) {
        $referenceNumber = '';
        $referenceNumberExists = true;

        do {
            $referenceNumber = "SH{$order->id}-" . str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
            $referenceNumberExists = (bool) Payment::where('reference_number', $referenceNumber)->count();
        } while ($referenceNumberExists);

        return $referenceNumber;
    }
}

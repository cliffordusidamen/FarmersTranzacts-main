<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'reference_number',
        'user_id',
        'items_amount',
        'delivery_amount',
        'delivery_days',
        'total_amount',
        'delivery_status_id',
        'order_notes',
    ];

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
     * Relationship: Belongs to delivery status
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deliveryStatus(): BelongsTo
    {
        return $this->belongsTo(DeliveryStatus::class, 'delivery_status_id');
    }

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
     * Relatioship: Has many vendor orders
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorOrders(): HasMany
    {
        return $this->hasMany(VendorOrder::class, 'order_id');
    }

    /**
     * Relationship: Payment(s) for order
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    /**
     * Relationship: Belongs to address
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    /**
     * Generate reference number for the order
     * 
     * @return string
     */
    public static function generateReferenceNumber() {
        $referenceNumber = '';
        $referenceNumberExists = true;

        do {
            $referenceNumber = \Illuminate\Support\Str::random(3) . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $referenceNumberExists = (bool) Order::where('reference_number', $referenceNumber)->count();
        } while ($referenceNumberExists);

        return strtoupper($referenceNumber);
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

    /**
     * Save data from cart
     * 
     * @param  \App\Services\ShoppingCartService  $cart
     * @param  array  $data
     * @return bool|self
     */
    public static function initCartOrder(
        \App\Services\ShoppingCartService $cart,
        array $data
    ) {

        $saved = false;
        $order = new Order();
        DB::transaction(function() use ($data, $cart, &$saved, &$order) {
            $orderItems = [];
            $vendorOrders = [];
            $vendorProducts = [];

            $addressData = collect($data)->only([
                'line_1', 'line_2', 'city',
                'country_id',
            ])->toArray();
            $state = $cart->getDeliveryState();
            $addressData['state_id'] = $state['id'];

            $address = Address::create($addressData);

            collect($cart->getItems())->map(function ($item, $key) use (&$orderItems, &$vendorProducts, $cart) {
                $orderItems[] = (new OrderItem([
                    'product_id'    => $item['product']['id'],
                    'quantity'      => $item['quantity'],
                    'unit_amount'   => $item['product']['price'],
                    'total_amount'  => $item['total'],
                    'delivery_amount'   => $item['delivery_amount'],
                    'delivery_days'   => $item['delivery_days'],
                    'grand_total_amount'  => $item['total_with_delivery_amount'],
                ]));

                if (!isset($vendorProducts[$item['product']['vendor_id']])) {
                    $vendorProducts[$item['product']['vendor_id']] = [];
                }
                $vendorProducts[$item['product']['vendor_id']][] = $item['product']['id'];
            });
            
            $order->forceFill([
                'reference_number' => self::generateReferenceNumber(),
                'user_id'     => auth()->user()->id,
                'address_id'  => $address->id,
                'items_amount'      => $cart->getGrandTotal(),
                'delivery_amount'      => $cart->getTotalDeliveryAmount(),
                'delivery_days'      => $cart->getTotalDeliveryDays(),
                'total_amount'      => $cart->getGrandTotalWithDelivery(),
                'order_notes' => $data['order_notes'] ?? null,
            ])->save();

            collect($cart->getVendorOrders())->map(function($vendorOrder, $i) use (&$vendorOrders, $address, $order) {
                $vendorOrders[] = new VendorOrder([
                    'reference_number' => "{$order->reference_number}-{$vendorOrder['vendor']['id']}",
                    'user_id'     => auth()->user()->id,
                    'address_id'  => $address->id,
                    'vendor_id'      => $vendorOrder['vendor']['id'],
                    'items_amount'      => $vendorOrder['amount'],
                    'delivery_amount'      => $vendorOrder['delivery_amount'],
                    'delivery_days'      => $vendorOrder['delivery_days'],
                    'total_amount'      => $vendorOrder['grand_total_amount'],
                ]);
            });


            $order->items()->saveMany($orderItems);
            $order->vendorOrders()->saveMany($vendorOrders);
            $order->vendorOrders()->get()->map(fn ($vOrder, $i) => (
                OrderItem::where('order_id', $order->id)
                    ->whereIn('product_id', $vendorProducts[$vOrder->vendor_id])
                    ->update([
                        'vendor_order_id' => $vOrder->id,
                    ])
            ));
            $saved = true;
        });

        return $saved ? $order : false;
    }

    /**
     * Filter orders by user_id
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser(Builder $query, int $user_id): Builder
    {
        return $query->where('user_id', $user_id);
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
}

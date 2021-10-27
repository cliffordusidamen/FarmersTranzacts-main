<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductDeliveryCost;
use App\Models\State;

class ShoppingCartService
{
    /**
     * Indicate if the order can be delivered
     * 
     * @var bool
     */
    private bool $canBeDelivered = true;

    /**
     * Indicate the overall delivery cost
     * 
     * @var double
     */
    private $totalDeliveryAmount = 0;

    /**
     * Indicate the overall days for the delivery
     * 
     * @var int
     */
    private int $totalDeliveryDays = 0;

    /**
     * Items
     * 
     * @var array
     */
    private array $items = [];

    /**
     * Items grouped by vendor
     * 
     * @var array
     */
    private array $vendorOrders  = [];

    /**
     * Delivery state
     * 
     * @var array
     */
    private array $state = [];

    /**
     * Grand total
     * 
     * @var float
     */
    private float $grandTotal = 0;

    /**
     * Grand total with delivery added
     * 
     * @var float
     */
    private float $grandTotalWithDelivery = 0;

    /**
     * Create instance of the shopping cart
     * 
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Initialize the shopping cart session data
     * 
     * @return array
     */
    public function initialize()
    {
        if (!request()->session()->has('cart')) {
            request()->session()->put('cart', ['items' => []]);
        }
        $this->items = request()->session()->get('cart.items', []);
        $this->state = request()->session()->get('cart.state', []);
        $this->canBeDelivered = request()->session()->get('cart.can_be_delivered', false);
        $this->totalDeliveryDays = request()->session()->get('cart.total_delivery_days', 0);
        $this->totalDeliveryAmount = request()->session()->get('cart.total_delivery_amount', 0);
        $this->grandTotal = request()->session()->get('cart.grand_total', 0);
        $this->vendorOrders  = request()->session()->get('cart.vendor_orders', []);
        $this->grandTotalWithDelivery = request()->session()->get('cart.grand_total_with_delivery', 0);

        return $this->getData();
    }

    /**
     * Get the data stored in the cart
     * 
     * @return array
     */
    public function getData(): array
    {
        $this->calculateGrandTotal();
        $this->calculateDeliveryCost();
        $cart = [
            'state' => $this->state,
            'items' => $this->items,
            'can_be_delivered' => $this->canBeDelivered,
            'total_delivery_days' => $this->totalDeliveryDays,
            'total_delivery_amount' => $this->totalDeliveryAmount,
            'grand_total' => $this->grandTotal,
            'grand_total_with_delivery' => $this->grandTotalWithDelivery,
            'vendor_orders' => $this->vendorOrders,
        ];
        request()->session()->put('cart', $cart);
        return request()->session()->get('cart');
    }

    /**
     * Add a product to the cart
     * 
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function addProduct(Product $product)
    {
        if (!isset($this->items[$product->id])) {
            $product->load('vendor');
            $this->items[$product->id] = [
                'product' => $product->toArray(),
                'quantity' => 1,
                'total' => (double) $product->price,
                'unit_amount' => (double) $product->price,
            ];

        } else {
            $this->items[$product->id]['total'] = $this->items[$product->id]['quantity']
                * $product->price;
        }
        $this->getData();
    }

    /**
     * Set all items to be placed in the cart
     * 
     * @param array
     * @return array
     */
    public function setItems(array $newItems): array
    {
        $items = [];
        foreach ($newItems as $id => $itemData) {
            $product = Product::with('vendor')->findOrFail($id);
            $items[$product->id] = [
                'product' => $product->toArray(),
                'quantity' => $itemData['quantity'],
                'unit_amount' => (double) $product->price,
                'total' => $itemData['quantity'] * $product->price,
            ];
        }
        $this->items = $items;
        request()->session()->forget('cart');
        return $this->getData();
    }

    /**
     * Get cart items
     * 
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Determine if the cart has items
     * 
     * @return bool
     */
    public function hasItems(): bool
    {
        return count($this->items) > 0;
    }

    /**
     * Calculate grand total
     * 
     * @return double
     */
    public function calculateGrandTotal()
    {
        $grandTotal = 0;
        if (isset($this->items)) {
            foreach ($this->items as $id => $item) {
                $grandTotal += $item['total'];
            }
        }
        $this->grandTotal = $grandTotal;
        request()->session()->put('cart.grand_total', $grandTotal);
        return $this->grandTotal;
    }

    /**
     * Remove a product, indicated by id, from the cart
     * 
     * @param  int  $productId
     * @return bool|array
     */
    public function removeProduct($productId)
    {
        if (isset($this->items[$productId])) {
            unset($this->items[$productId]);
            return $this->getData();
        }

        return false;
    }

    /**
     * Clear the cache
     * 
     * @return array
     */
    public function clear()
    {
        $this->setItems([]);
        return $this->getData();
    }

    /**
     * Get the currently set delivery state
     * 
     * @return array
     */
    public function getDeliveryState(): array
    {
        return $this->state;
    }

    /**
     * Set the delivery state
     * 
     * @param  int  $id
     * @return array
     */
    public function setDeliveryState(int $id): array
    {
        $this->state = State::with('zone')->find($id)->toArray();
        return $this->getData();
    }

    /**
     * calculate the delivery cost of each item
     * 
     * @return void
     */
    public function calculateDeliveryCost()
    {
        if (empty($this->state['zone']['name'])) {
            return;
        }
        $this->grandTotalWithDelivery = $this->grandTotal;
        $this->totalDeliveryAmount = 0;
        $this->totalDeliveryDays = 0;
        $deliveryZoneKey = str_replace('-', '_', $this->state['zone']['name']);
        $deliveryCostKey = strtolower("{$deliveryZoneKey}_cost");
        $deliveryDaysKey = strtolower("{$deliveryZoneKey}_delivery_days");
        $this->vendorOrders = [];
        $this->canBeDelivered = true;
        foreach ($this->items as $key => $item) {
            $productDeliveryCost = ProductDeliveryCost::where('product_id', $item['product']['id'])
                ->where('minimum_quantity', '<=', $item['quantity'])
                ->where('maximum_quantity', '>=', $item['quantity'])
                ->first();

            $canBeDelivered = $productDeliveryCost && !empty($productDeliveryCost->$deliveryCostKey);

            $this->items[$key]['can_be_delivered'] = $canBeDelivered;

            $this->items[$key]['delivery_amount'] = $canBeDelivered
                ? (float) $productDeliveryCost->$deliveryCostKey
                : 0;

            $this->items[$key]['delivery_days'] = $canBeDelivered
                ? (int) $productDeliveryCost->$deliveryDaysKey
                : 0;

            $this->items[$key]['total_with_delivery_amount'] = 
                $this->items[$key]['delivery_amount'] + $this->items[$key]['total'];

            $this->canBeDelivered = !$canBeDelivered ? false : $this->canBeDelivered;
            $this->totalDeliveryAmount += $this->items[$key]['delivery_amount'];
            $this->grandTotalWithDelivery += $this->items[$key]['delivery_amount'];
            $this->totalDeliveryDays = max(
                $this->totalDeliveryDays,
                $this->items[$key]['delivery_days']
            );

            $vendorId = $item['product']['vendor_id'];
            if (!isset($this->vendorOrders[$vendorId])) {
                $this->vendorOrders[$vendorId] = [
                    'vendor' => $item['product']['vendor'],
                    'items' => [],
                    'amount' => 0,
                    'delivery_amount' => 0,
                    'delivery_days' => 0,
                    'grand_total_amount' => 0,
                ];
            }

            $this->vendorOrders[$vendorId]['items'][] = $item;
            $this->vendorOrders[$vendorId]['amount'] += $item['total'] ?? 0;
            $this->vendorOrders[$vendorId]['delivery_amount'] += $item['delivery_amount'] ?? 0;

            $this->vendorOrders[$vendorId]['delivery_days'] = max(
                $item['delivery_days'] ?? 0,
                $this->vendorOrders[$vendorId]['delivery_days']
            );

            $this->vendorOrders[$vendorId]['grand_total_amount']
                += (($item['delivery_amount'] ?? 0) + ($item['total'] ?? 0));
        }
    }

    /**
     * Get the grand total
     * 
     * @return  float
     */
    public function getGrandTotal()
    {
        return $this->grandTotal;
    }

    /**
     * Get the total delivery amount
     * 
     * @return  float
     */
    public function getTotalDeliveryAmount()
    {
        return $this->totalDeliveryAmount;
    }

    /**
     * Get the total delivery days
     * 
     * @return  float
     */
    public function getTotalDeliveryDays()
    {
        return $this->totalDeliveryDays;
    }

    /**
     * Get the grand total with delivery
     * 
     * @return  float
     */
    public function getGrandTotalWithDelivery()
    {
        return $this->grandTotalWithDelivery;
    }

    /**
     * Determine if the items in the cart are deliverable to the set state
     * 
     * @return  bool
     */
    public function canBeDelivered()
    {
        return $this->canBeDelivered;
    }
}
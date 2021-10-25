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

        return $this->getData();
    }

    /**
     * Get the data stored in the cart
     * 
     * @return array
     */
    public function getData(): array
    {
        $cart = [
            'state' => $this->state,
            'items' => $this->items,
            'can_be_delivered' => $this->canBeDelivered,
            'total_delivery_days' => $this->totalDeliveryDays,
            'total_delivery_amount' => $this->totalDeliveryAmount,
            'grand_total' => $this->getGrandTotal()
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
            $this->items[$product->id] = [
                'product' => $product->toArray(),
                'quantity' => 1,
                'total' => $product->price,
            ];
        } else {
            $this->items[$product->id]['total'] = $this->items[$product->id]['quantity']
                * $product->price;
        }
        $this->updateDeliveryCost();
        request()->session()->put('cart.items', $this->items);
        $this->getGrandTotal();
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
            $product = Product::findOrFail($id);
            $items[$product->id] = [
                'product' => $product->toArray(),
                'quantity' => $itemData['quantity'],
                'unit_amount' => $product->price,
                'total' => $itemData['quantity'] * $product->price,
            ];
        }
        $this->items = $items;
        request()->session()->forget('cart');
        $this->updateDeliveryCost();
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
    public function getGrandTotal()
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
            $this->updateDeliveryCost();
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
        return $this->setItems([]);
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
        $this->updateDeliveryCost();
        return $this->getData();
    }

    /**
     * calculate the delivery cost of each item
     * 
     * @return void
     */
    public function updateDeliveryCost()
    {
        if (empty($this->state['zone']['name'])) {
            return;
        }
        $deliveryZoneKey = str_replace('-', '_', $this->state['zone']['name']);
        $deliveryCostKey = "{$deliveryZoneKey}_cost";
        $deliveryDaysKey = "{$deliveryZoneKey}_delivery_days";
        foreach ($this->items as $key => $item) {
            $productDeliveryCost = ProductDeliveryCost::where('product_id', $item['product']['id'])
                ->where('minimum_quantity', '<=', $item['quantity'])
                ->where('maximum_quantity', '>=', $item['quantity'])
                ->first();

            $canBeDelivered = $productDeliveryCost && !empty($productDeliveryCost->$deliveryCostKey);

            $this->items[$key]['can_be_delivered'] = $canBeDelivered;

            $this->items[$key]['delivery_amount'] = $canBeDelivered
                ? $productDeliveryCost->$deliveryDaysKey
                : 0;

            $this->items[$key]['delivery_days'] = $canBeDelivered
                ? $productDeliveryCost->$deliveryDaysKey
                : 0;

            $this->canBeDelivered = $canBeDelivered ? true : $this->canBeDelivered;
            $this->totalDeliveryAmount += $this->items[$key]['delivery_amount'];
            $this->totalDeliveryDays = max(
                $this->totalDeliveryDays,
                $this->items[$key]['delivery_days']
            );
        }
    }
}
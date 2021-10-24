<?php

namespace App\Services;

use App\Models\Product;
use App\Models\State;

class ShoppingCartService
{
    /**
     * Items
     * 
     * @var array
     */
    private array $items = [];

    /**
     * Delivery state
     * 
     * @var  \App\Models\State
     */
    private ?State $state;

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
        $this->items = request()->session()->get('cart.items') ?? [];

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
     * @return \App\Models\State
     */
    public function getDeliveryState(): ?State
    {
        return $this->state;
    }

    /**
     * Set the delivery state
     * 
     * @param  int  $id
     * @return void
     */
    public function setDeliveryState(int $id)
    {
        $this->state = State::find($id);
    }
}
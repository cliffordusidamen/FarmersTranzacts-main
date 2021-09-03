<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Product;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ShoppingCartController extends Controller
{
    /**
     * Create controller instance
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified'])->only([
            'checkout',
        ]);
    }
    /**
     * View cart content
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request)
    {
        $cart = $request->session()->get('cart', ['items' => []]);
        return Inertia::render('Cart/View.vue', compact('cart'));
    }
    /**
     * Add item to cart
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addItem(Request $request)
    {

        $data = $request->all();
        $data = Validator::make($data, [
            'product_id' => 'required|numeric|exists:products,id',
            'quantity' => 'sometimes|numeric|min:1|max:999999'
        ])->validate();
        $cart = $request->session()->get('cart', ['items' => []]);

        $product = Product::availableForSale()->findOrFail($data['product_id']);
        if (!$product) {
            flashMessage('danger', "'{$product->name}' is not available at the moment!");
            return back();
        }
        if (!isset($cart['items'][$product->id])) {
            $cart['items'][$product->id] = [
                'product' => $product->toArray(),
                'quantity' => 1,
            ];
        }

        $cart['items'][$product->id]['total'] = $cart['items'][$product->id]['quantity']
            * $product->price;

        $cart = $this->_updateCartGrandTotal($cart);
        $request->session()->put('cart', $cart);
        flashMessage('success', "<strong>{$product->name}</strong> has been added to your cart!");
        return back();
    }
    /**
     * Remove item to cart
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeItem(Request $request)
    {

        $data = $request->all();
        $data = Validator::make($data, [
            'product_id' => 'required|numeric|exists:products,id',
        ])->validate();
        $cart = $request->session()->get('cart', ['items' => []]);

        $product = Product::availableForSale()->findOrFail($data['product_id']);

        if (isset($cart['items'][$data['product_id']])) {
            unset($cart['items'][$data['product_id']]);
        }

        $cart = $this->_updateCartGrandTotal($cart);
        $request->session()->put('cart', $cart);
        flashMessage('success', "<strong>{$product->name}</strong> has been removed from your cart!");
        return back();
    }

    /**
     * Update all items in the cart
     * @param  \Illuminate\Http\Request  $request
     */
    public function updateCartItems(Request $request)
    {

        $data = $request->all();
        $data = Validator::make($data, [
            'items.*.product_id' => 'required|numeric|exists:products,id',
            'items.*.quantity' => 'sometimes|numeric|min:1|max:999999'
        ])->validate();

        $cart = ['items' => []];

        foreach ($data['items'] as $id => $itemData) {
            $product = Product::findOrFail($id);
            $cart['items'][$product->id] = [
                'product' => $product->toArray(),
                'quantity' => $itemData['quantity'],
                'total' => $itemData['quantity'] * $product->price,
            ];
        }
        $cart = $this->_updateCartGrandTotal($cart);
        $request->session()->forget('cart');
        $request->session()->put('cart', $cart);
        Log::info(['Updated' => $cart]);
        flashMessage('success', "Cart updated successfully!");
        return back();
    }

    /**
     * Calculate grand total price of items in the cart
     * 
     * @return array
     */
    public function _updateCartGrandTotal($cart): array
    {
        $grandTotal = 0;
        if (isset($cart['items'])) {
            foreach ($cart['items'] as $id => $item) {
                $grandTotal += $item['total'];
            }
        }

        $cart['grand_total'] = $grandTotal;
        
        return $cart;
    }

    /**
     * Checkout page
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function checkout(Request $request)
    {
        $cart = $request->session()->get('cart');

        if (!isset($cart['items']) || count($cart['items']) == 0) {
            return redirect()->route('shopping_cart.view');
        }

        $cart = $request->session()->get('cart', ['items' => []]);
        $app_states = State::pluck('state_name', 'id');
        return Inertia::render('Cart/Checkout.vue', compact(
            'cart', 'app_states'
        ));
    }

    /**
     * Proceed to payment
     * 
     * @param  \App\Http\Requests\OrderRequest  $request
     */
    public function order(OrderRequest $request)
    {
        flashMessage('success', 'Valid order request data! Will implement payment soon!');
        return back()->withInput();
    }
}

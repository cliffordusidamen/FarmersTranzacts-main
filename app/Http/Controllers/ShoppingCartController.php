<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\State;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $showMenu = false;
        return Inertia::render('Cart/View.vue', compact('cart', 'showMenu'));
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
        $showMenu = false;
        return Inertia::render('Cart/Checkout.vue', compact(
            'cart', 'app_states', 'showMenu'
        ));
    }

    /**
     * Proceed to payment
     * 
     * @param  \App\Http\Requests\OrderRequest  $request
     */
    public function order(OrderRequest $request)
    {
        $cart = $request->session()->get('cart');

        if (!isset($cart['items']) || count($cart['items']) == 0) {
            return redirect()->route('shopping_cart.view');
        }

        $saved = false;
        $order = new Order();
        DB::transaction(function() use ($request, $cart, &$saved, &$order) {
            $totalAmount = 0;
            $items = [];
            
            collect($cart['items'])->map(function ($item, $key) use (&$items, &$totalAmount) {
                $totalAmount += $item['total'];
                $items[] = (new OrderItem([
                    'product_id' => $item['product']['id'],
                    'quantity' => $item['quantity'],
                    'unit_amount' => $item['product']['price'],
                    'total_amount' => $item['quantity'] * $item['product']['price'],
                ]));
            });

            Log::info(['Order request', $request->all()]);
            
            $order->forceFill([
                'user_id' => auth()->user()->id,
                'amount' => $totalAmount,
                'order_notes' => $request->input('order_notes', null),
            ])->save();

            $order->items()->saveMany($items);
            $saved = true;
        });

        if (!$saved) {
            flashMessage('danger', 'Payment initialization failed! Please, try again.');
            return back()->withInput();
        }

        try {
            $result = (new PaymentService)->intializeTransaction(auth()->user(), $order);
        } catch (Exception $e) {
            $order->delete();
            flashMessage('danger', 'Payment initialization failed! Please, try again.');
            return back()->withInput();
        }

        return Inertia::location($result['authorization_url']);
    }

    /**
     * Cancel payment
     * 
     * @param  \App\Models\Order  $order 
     */
    public function paymentCancel(Order $order)
    {
        $cancel_response = request()->query();
        $order->delete();

        flashMessage('danger', 'Payment cancelled!');
        return redirect()->route('shopping_cart.view');
    }

    /**
     * Verify payment
     * 
     * 
     */
    public function paymentVerify()
    {
        $reference = request()->query('trxref')
            ?? request()->query('reference');

        Log::info(compact('reference'));

        if (!$reference) {
            return redirect()->route('shopping_cart.view');
        }

        $transactionDetails = (new PaymentService)->verify($reference);

        if (isset($transactionDetails['status'])) {
            $order = Order::findOrFail($transactionDetails['data']['metadata']['order_id']);
            // save payment information to database
            $payment = Payment::create([
                'order_id' => $order->id,
                'reference_number' => Order::generatePaymentReference($order),
                'provider_reference' => $reference,
                'status' => $transactionDetails['data']['status'],
                'user_id' => request()->user()->id,
                'amount' => $transactionDetails['data']['amount']/100,
                'response_json' => json_encode($transactionDetails),
            ]);

            if ($transactionDetails['data']['status'] == 'success') {
                // Send email to the user
                // send email to the admin
                // event(new OrderCompletedEvent($order));
                flashMessage('success', 'Payment successful! Order will be delivered in due time.');
                request()->session()->put('cart', []);
                // Redirect to orders page
                return redirect()->route('shopping_cart.view');
            }
        }
        
        flashMessage('danger', 'Payment was not successful!');
        // Clear the cart
        request()->session()->put('cart', []);
        // Redirect to orders page
        return redirect()->route('shopping_cart.view');
    }
}

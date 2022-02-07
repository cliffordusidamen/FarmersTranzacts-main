<?php

namespace App\Http\Controllers;

use App\Events\OrderCompletedEvent;
use App\Http\Requests\OrderRequest;
use App\Models\Address;
use App\Models\AppSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\State;
use App\Services\PaymentService;
use App\Services\ShoppingCartService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ShoppingCartController extends Controller
{
    /**
     * Shopping cart service
     * 
     * @var  \App\Services\ShoppingCartService
     */
    private ShoppingCartService $cart;

    /**
     * Create controller instance
     */
    public function __construct(Request $request)
    {
        $this->middleware(['auth:sanctum', 'verified'])->only([
            'checkout', 'paymentVerify',
        ]);
    }
    
    /**
     * Initialize the cart
     */
    public function initCart()
    {
        $this->cart = new ShoppingCartService();
    }

    /**
     * View cart content
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request)
    {
        $this->initCart();
        $cart = $this->cart->getData();
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

        $product = Product::availableForSale()
            ->with('vendor')
            ->findOrFail($data['product_id']);

        if (!$product) {
            flashMessage('danger', "'{$product->name}' is not available at the moment!");
            return back();
        }
        $this->initCart();
        $this->cart->addProduct($product, $request->quantity);
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

        $product = Product::availableForSale()->findOrFail($data['product_id']);
        $this->initCart();
        $this->cart->removeProduct($product->id);
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
        $this->initCart();
        $this->cart->setItems($data['items']);
        flashMessage('success', "Cart updated successfully!");
        return back();
    }

    /**
     * Set the state where the shopper wants the items delivered
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function setDeliveryState(Request $request)
    {
        $this->validate($request, [
            'state_id' => 'required|exists:states,id',
            'product_id' => 'sometimes|nullable|exists:products,id'
        ]);
        $this->initCart();

        if ($request->input('product_id')) {
            $product = Product::availableForSale()->findOrFail($request->input('product_id'));
            $this->cart->addProduct($product);
        }
        $this->cart->setDeliveryState($request->input('state_id'));

        flashMessage('success', 'Delivery state updated!');
        return back();
    }

    /**
     * Checkout page
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function checkout(Request $request)
    {
        $this->initCart();
        if (
            !$this->cart->hasItems()
            || !$this->cart->canBeDelivered()
        ) {
            return redirect()->route('shopping_cart.view');
        }

        $cart = $this->cart->getData();
        $app_states = State::pluck('state_name', 'id');
        $showMenu = false;

        $referralList = [];

        if (authUserIsAgent()) {
            $referralList = $request->user()->referrals()->get()->mapWithKeys(fn ($referral) => (
                [$referral->id => $referral->full_name]
            ));
        }

        return Inertia::render('Cart/Checkout.vue', compact(
            'cart', 'app_states', 'showMenu', 'referralList'
        ));
    }

    /**
     * Proceed to payment
     * 
     * @param  \App\Http\Requests\OrderRequest  $request
     */
    public function order(OrderRequest $request)
    {
        $this->initCart();
        if (!$this->cart->hasItems()) {
            return redirect()->route('shopping_cart.view');
        }

        $payOnDelivery = $request->input('checkout_to') == 'pay_on_delivery';

        $order = $payOnDelivery 
            ? Order::initCartOrder($this->cart, $request->all(), $payOnDelivery)
            : null;

        if ($payOnDelivery && !isset($order->id)) {
            flashMessage('danger', 'Order was not successful! Please, try again.');
            return back()->withInput();
        }

        if ($payOnDelivery) {
            $payOnDeliveryEnabled = (bool) AppSetting::getSetting('pay_on_delivery_allowed', false);

            if (!$payOnDeliveryEnabled) {
                flashMessage('danger', 'Pay-on-delivery order failed! Please, try again.');
                return back()->withInput();
            }
            event(new OrderCompletedEvent($order));
            flashMessage('success', 'Order successful! Order will be delivered in due time. Please, ensure you have cash for payment on delivery.');
            $this->cart->clear();
            return redirect()->route('shopping_cart.view');
        }

        try {
            $result = (new PaymentService)->intializeTransaction(
                auth()->user(),
                $request->all(),
                $this->cart->getGrandTotalWithDelivery()
            );
        } catch (Exception $e) {
            return $e->getMessage();
        }

        if (!isset($result['authorization_url'])) {
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
        $order->delete();

        flashMessage('danger', 'Payment cancelled!');
        return redirect()->route('shopping_cart.view');
    }

    /**
     * Verify payment
     * 
     */
    public function paymentVerify()
    {
        $this->initCart();
        $reference = request()->query('trxref')
            ?? request()->query('reference');

        if (!$reference) {
            return redirect()->route('shopping_cart.view');
        }

        $transactionDetails = (new PaymentService)->verify($reference);

        Log::info($transactionDetails);
        $data = $transactionDetails['data']['metadata']['request_data'];
        $payOnDelivery = $data['checkout_to'] == 'pay_on_delivery';

        $order = Order::initCartOrder($this->cart, $data, $payOnDelivery);
        if (isset($transactionDetails['status'])) {

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
                event(new OrderCompletedEvent($order));
                flashMessage('success', 'Payment successful! Order will be delivered in due time.');
                $this->cart->clear();
                return redirect()->route('shopping_cart.view');
            }
        }
        
        flashMessage('danger', 'Payment was not successful!');
        $this->cart->clear();
        return redirect()->route('shopping_cart.view');
    }
}

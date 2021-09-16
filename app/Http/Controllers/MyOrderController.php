<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MyOrderController extends Controller
{
    /**
     * Create instance of the controller
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    /**
     * List orders from most recent
     * 
     * @return \Inertia\Response
     */
    public function index()
    {
        $orders = Order::byUser(auth()->user()->id)
            ->withCount(['items'])
            ->with(['shippingStatus'])
            ->orderByDesc('id')
            ->paginate(10);

        return Inertia::render('MyOrders/Index', compact('orders'));
    }

    /**
     * View details of an order
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Inertia\Response
     */
    public function show(Request $request, Order $order)
    {
        abort_if($order->user_id != $request->user()->id, 404, 'Page not found!');

        $order->load([
            'payments',
            'shippingStatus',
            'items',
            'items.product',
            'items.product.vendor',
        ]);

        return Inertia::render('MyOrders/Show', compact('order'));
    }
}

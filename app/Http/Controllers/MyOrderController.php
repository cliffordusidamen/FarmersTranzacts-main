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

        return Inertia::render('MyOrder/Index', compact('orders'));
    }
}

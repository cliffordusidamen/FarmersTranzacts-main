<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Create instance of the controller
     */
    public function __construct()
    {
        // To do: add middleware for adminstrators only
        $this->middleware(['auth:sanctum', 'frontend']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO: Implement filering by date range, delivery status, reference number, buyer name
        $orders = Order::with([
            'deliveryStatus',
            'user',
            'vendorOrders',
            'vendorOrders.items',
            'vendorOrders.items.product',
            'vendorOrders.items.product.packaging',
        ])
        ->paginate(10);

        return new OrderCollection($orders);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $order = Order::with([
            'user',
            'address',
            'address.state',
            'vendorOrders',
            'vendorOrders.vendor',
            'vendorOrders.items',
            'vendorOrders.items.product',
            'vendorOrders.items.product.packaging',
            'vendorOrders.items.product.category',
            'vendorOrders.items.product.subcategory',
        ])
        ->findOrFail($id);

        return response()->json([
            'order' => new OrderResource($order),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\VendorOrderCollection;
use App\Http\Resources\VendorOrderResource;
use App\Models\VendorOrder;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
    /**
     * Create instance of the controller
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'frontend']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vendorOrders = $request->user()->vendors()->first()->orders()
            ->with([
                'order', 'user', 'items'
            ])
            ->paginate(10);

        return new VendorOrderCollection($vendorOrders);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendorOrder = request()->user()
            ->vendors()
            ->first()
            ->orders()
            ->with([
                'order', 'user', 'items',
                'items.product',
            ])
            ->findOrFail((int) $id);

        return response()->json([
            'vendorOrder' => new VendorOrderResource($vendorOrder),
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

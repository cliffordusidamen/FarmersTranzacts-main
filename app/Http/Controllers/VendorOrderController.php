<?php

namespace App\Http\Controllers;

use App\Events\VendorOrderDeliveredEvent;
use App\Events\VendorOrderTransitEvent;
use App\Http\Resources\VendorOrderCollection;
use App\Http\Resources\VendorOrderResource;
use App\Models\DeliveryStatus;
use App\Models\VendorOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $vendor = $request->user()->vendors()->first();
        $vendorOrders = VendorOrder::cancellationPeriodElapsed()
            ->notCancelled()
            ->forVendor($vendor->id)
            ->with([
                'order', 'user', 'items'
            ])
            ->paginate(10);
        
        if ($vendorOrders->total()) {
            $vendorOrders->each(function ($vendorOrder) {
                $vendorOrder->append('transitable');
                $vendorOrder->append('deliverable');
            });
        }

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

        $vendorOrder->append([
            'transitable',
            'deliverable',
        ]);

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

    /**
     * Set order status as in-transit
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function transit(Request $request, int $id)
    {
        $this->validate($request, [
            'transit_note' => 'sometimes|nullable|string|max:1000',
        ]);

        $vendorOrder = request()->user()
            ->vendors()
            ->first()
            ->orders()
            ->findOrFail((int) $id);

        if (!$vendorOrder->transitable) {
            if (DeliveryStatus::IN_TRANSIT_ID == $vendorOrder->delivery_status_id) {
                return response()->json([
                    'message' => 'Order has been set as in-transit',
                ]);
            }
            throw new \Exception('Order status cannot be set as in-transit.');

        }

        $saved = false;
        DB::transaction(function() use ($request, $vendorOrder, &$saved) {

            $vendorOrder->forceFill([
                'delivery_status_id' => DeliveryStatus::IN_TRANSIT_ID,
                'transit_note' => $request->transit_note,
            ]);

            $saved = $vendorOrder->save();
            if ($saved) {
                event(new VendorOrderTransitEvent($vendorOrder));
            }
        });

        if ($saved) {
            return response()->json([
                'message' => 'Order has been set as in-transit',
            ]);
        }

        throw new \Exception('Failed to set order as in-transit. Please, try again.');
    }

    /**
     * Set order status as delivered
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delivery(Request $request, int $id)
    {
        $this->validate($request, [
            'delivery_note' => 'sometimes|nullable|string|max:1000',
        ]);

        $vendorOrder = request()->user()
            ->vendors()
            ->first()
            ->orders()
            ->findOrFail((int) $id);

        if (!$vendorOrder->deliverable) {
            if (DeliveryStatus::IN_TRANSIT_ID == $vendorOrder->delivery_status_id) {
                return response()->json([
                    'message' => 'Order has been set as delivered',
                ]);
            }
            throw new \Exception('Order status cannot be set as delivered.');

        }

        $saved = false;
        DB::transaction(function() use ($request, $vendorOrder, &$saved) {
            $vendorOrder->forceFill([
                'delivery_status_id' => DeliveryStatus::DELIVERED_ID,
                'delivery_note' => $request->delivery_note,
            ]);

            $saved = $vendorOrder->save();

            if ($saved) {
                $vendorOrder->order->updateDeliveryStatus();
                event(new VendorOrderDeliveredEvent($vendorOrder));
            }
        });

        if ($saved) {
            return response()->json([
                'message' => 'Order has been set as delivered',
            ]);
        }

        throw new \Exception('Failed to set order as delivered. Please, try again.');
    }
}

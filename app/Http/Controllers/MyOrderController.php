<?php

namespace App\Http\Controllers;

use App\Events\VendorOrderReviewedEvent;
use App\Mail\VendorOrderCanceledNotifyAdminMail;
use App\Models\AppSetting;
use App\Models\DeliveryStatus;
use App\Models\Order;
use App\Models\VendorOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
            ->with(['deliveryStatus'])
            ->orderByDesc('id')
            ->paginate(10);
            
        if ($orders->total()) {
            $orders->each(function ($order) {
                $order->append('cancellable');
            });
        }

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
            'deliveryStatus',
            'vendorOrders',
            'vendorOrders.vendor',
            'vendorOrders.items',
            'vendorOrders.items.product',
            'vendorOrders.deliveryStatus',
        ]);

        $order->vendorOrders->each(fn($vendorOrder) => (
            $vendorOrder->append([
                'cancellable',
                'can_submit_review',
                'rating_remark',
            ])
        ));

        $ratingOptions = AppSetting::getReviewRatingOptions();

        $order->append('cancellable');
        

        return Inertia::render('MyOrders/Show', compact('order', 'ratingOptions'));
    }

    /**
     * Display form for cancelling vendor order
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VendorOrder  $vendorOrder
     * @return \Inertia\Response
     */
    public function showCancelVendorOrder(
        Request $request,
        VendorOrder $vendorOrder
    ) {
        $vendorOrder->append('cancellable');

        if (!$vendorOrder->cancellable) {
            $maxCancellationHours = (int) AppSetting::getSetting(AppSetting::CANCEL_ORDER_HOURS);
            flashMessage('warning', "You cannot cancel this order as the allowed window of {$maxCancellationHours} hours has elapsed.");
            return redirect()->route('my_orders.show', [
                'order' => $vendorOrder->order,
            ]);
        }
        $vendorOrder->load([
            'order',
            'vendor',
            'items',
            'items.product',
            'deliveryStatus',
        ]);

        return Inertia::render('MyOrders/CancelVendorOrder', compact('vendorOrder'));
    }

    /**
     * Cancel vendor order
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VendorOrder  $vendorOrder
     * @return \Inertia\Response
     */
    public function cancelVendorOrder(
        Request $request,
        VendorOrder $vendorOrder
    ) {
        $vendorOrder->append('cancellable');

        if (!$vendorOrder->cancellable) {
            $maxCancellationHours = (int) AppSetting::getSetting(AppSetting::CANCEL_ORDER_HOURS);
            flashMessage('warning', "You cannot cancel this order as the window of '{$maxCancellationHours} hours' is elapsed.");
            return redirect()->route('my_orders.show', [
                'order' => $vendorOrder->order,
            ]);
        }

        $this->validate($request, [
            'cancel_reason' => 'required|min:3|max:255',
        ]);

        $saved = false;
        DB::transaction(function() use ($request, $vendorOrder, &$saved) {
            $vendorOrder->delivery_status_id = DeliveryStatus::CANCELED_ID;
            $vendorOrder->cancel_reason = $request->cancel_reason;
            $saved = $vendorOrder->save();

            if ($saved) {
                $vendorOrder->order->updateDeliveryStatus();
                $adminOrderMail = new VendorOrderCanceledNotifyAdminMail($vendorOrder);
                Mail::to(config('mail.from.address'))->send($adminOrderMail);
            }
        });

        if ($saved) {
            flashMessage('success', 'Seller order canceled!');
            return redirect()->route('my_orders.show', [
                'order' => $vendorOrder->order,
            ]);
        }

        flashMessage('warning', 'Seller order was not canceled. You may try again!');
        return back();
    }

    /**
     * Submit review for vendor order
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VendorOrder  $vendorOrder
     * @return \Inertia\Response
     */
    public function reviewVendorOrder(
        Request $request,
        VendorOrder $vendorOrder
    ) {
        $vendorOrder->append('can_submit_review');

        if (!$vendorOrder->can_submit_review) {
            return back();
        }

        $this->validate($request, [
            'customer_review_rating' => 'required|integer|min:1|max:5',
            'customer_review_comment' => 'required|min:3|max:255',
        ]);

        $saved = false;
        DB::transaction(function() use ($request, $vendorOrder, &$saved) {
            $vendorOrder->customer_review_rating = $request->customer_review_rating;
            $vendorOrder->customer_review_comment = $request->customer_review_comment;
            $vendorOrder->customer_reviewed_at = now();
            $saved = $vendorOrder->save();
        });

        if ($saved) {
            flashMessage('success', 'Review submitted!');
            event(new VendorOrderReviewedEvent($vendorOrder));
            return back();
        }

        flashMessage('warning', 'Review was not submitted. You may try again!');
        return back();
    }
}

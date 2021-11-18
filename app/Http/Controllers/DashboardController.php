<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Designation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Create instance of the controller
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'frontend']);
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if (authUserIsShopper()) {
            return redirect('/');
        }

        if (authUserIsVendor()) {
            return $this->_vendor();
        }

        if (authUserIsAgent()) {
            return $this->_agent();
        }

        return $this->_admin();
    }

    /**
     * Display dashboard for vendor
     * 
     * @return \Illuminate\Http\Response
     */
    public function _vendor()
    {
        $showCategories = false;
        $user_vendor = auth()->user()->vendors()->first();
        $approvalPending = $user_vendor->approval_status == Vendor::STATUS_PENDING;
        $approvalDisapproved = $user_vendor->approval_status == Vendor::STATUS_DISAPPROVED;
        $user_vendor->append('storage_used');
        $storageAllowed = AppSetting::getSetting(AppSetting::VENDOR_STORAGE_LIMIT) * 1_000_000;
        $numberOfProducts = $user_vendor->products()->count();

        $topProducts = OrderItem::ofDeliveredVendorOrders($user_vendor->id)
            ->thisMonth()
            ->with('product')
            ->rankProducts()
            ->take(5)
            ->get();

        $salesTrend = VendorOrder::getSalesTrend(6, $user_vendor->id);

        $ordersThisMonth = VendorOrder::forVendor($user_vendor->id)
            ->notCancelled($user_vendor->id)
            ->thisMonth()
            ->count();

        $earningsThisMonth = OrderItem::ofDeliveredVendorOrders($user_vendor->id)
            ->thisMonth()
            ->sum('total_amount');

        $topOrders = VendorOrder::thisMonth()
            ->with(['deliveryStatus', 'user', 'items'])
            ->orderBy('total_amount', 'desc')
            ->take(5)
            ->get();

        $responseData = compact(
            'showCategories',
            'user_vendor',
            'approvalPending',
            'approvalDisapproved',
            'storageAllowed',
            'numberOfProducts',
            'salesTrend',
            'topProducts',
            'topOrders',
            'earningsThisMonth',
            'ordersThisMonth'
        );

        if (request()->wantsJson()) {
            return response()->json($responseData);
        }

        return Inertia::render('Dashboards/VendorDashboard', $responseData);
    }

    /**
     * Display dashboard for admin
     * 
     * @return \Illuminate\Http\Response
     */
    public function _admin()
    {
        $showCategories = false;

        $shoppersCount = User::designated(Designation::SHOPPER_NAME)->count();
        $sellersCount = User::designated(Designation::SELLER_NAME)->count();
        $productsCount = Product::availableForSale()->count();
        $topSellers = VendorOrder::delivered()
            ->thisMonth()
            ->with('vendor')
            ->orderBy('total_amount', 'desc')
            ->groupBy('vendor_id')
            ->addSelect(
                'vendor_id',
                DB::raw('SUM(total_amount) as total_amount'),
                DB::raw('COUNT(id) as total_orders'),
            )
            ->take(5)
            ->get();

        $topProducts = OrderItem::ofDeliveredVendorOrders()
            ->thisMonth()
            ->with('product', 'product.vendor')
            ->rankProducts()
            ->take(5)
            ->get();

        $salesTrend = VendorOrder::getSalesTrend(6);

        $ordersThisMonth = Order::thisMonth()->count();

        $responseData = compact(
            'shoppersCount',
            'sellersCount',
            'topSellers',
            'topProducts',
            'productsCount',
            'salesTrend',
            'ordersThisMonth',
        );

        if (request()->wantsJson()) {
            return response()->json($responseData);
        }
        return Inertia::render('Dashboards/AdminDashboard', compact(
            'showCategories'
        ));
    }

    /**
     * Display dashboard for agent
     * 
     * @return \Illuminate\Http\Response
     */
    public function _agent()
    {
        // TODO: get total amount spent by agent for past 8 months
        $spendingTrend = VendorOrder::getUserSpendingTrend(
            8, request()->user()->id);

        $mostOrderedProduct = OrderItem::ofDeliveredVendorOrders()
            ->thisMonth()
            ->with('product', 'product.vendor')
            ->rankProducts()
            ->take(5)
            ->get();

        // TODO: get most ordered product
        // TODO: get most patronized seller
        return Inertia::render('Dashboards/AgentDashboard', compact(
            'spendingTrend'
        ));
    }
}

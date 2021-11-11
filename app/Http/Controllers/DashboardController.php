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

        return $this->_admin();
    }

    /**
     * Display dashboard for vendor
     * 
     * @return \Illuminate\Http\Response
     */
    public function _vendor()
    {
        // Set view based on the role
        $showCategories = false;
        $user_vendor = auth()->user()->vendors()->first();
        $approvalPending = $user_vendor->approval_status == Vendor::STATUS_PENDING;
        $approvalDisapproved = $user_vendor->approval_status == Vendor::STATUS_DISAPPROVED;
        $user_vendor->append('storage_used');
        // $user_vendor->append('current_month_orders');
        $storageAllowed = AppSetting::getSetting(AppSetting::VENDOR_STORAGE_LIMIT) * 1_000_000;
        $numberOfProducts = $user_vendor->products()->count();

        $responseData = compact(
            'showCategories', 'user_vendor', 'approvalPending', 'approvalDisapproved',
            'storageAllowed', 'numberOfProducts'
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
            ->groupBy('product_id')
            ->orderBy('total_orders', 'desc')
            ->addSelect(
                '*',
                'product_id',
                DB::raw('SUM(total_amount) as sum_total_amount'),
                DB::raw('SUM(quantity) as sum_quantity'),
                DB::raw('COUNT(id) as total_orders'),
            )
            ->take(5)
            ->get();

        $salesTrend = Order::getSalesTrend();

        $responseData = compact(
            'shoppersCount', 'sellersCount', 'topSellers', 'topProducts', 'productsCount', 'salesTrend',
        );

        if (request()->wantsJson()) {
            return response()->json($responseData);
        }
        return Inertia::render('Dashboards/AdminDashboard', compact(
            'showCategories'
        ));

    }
}

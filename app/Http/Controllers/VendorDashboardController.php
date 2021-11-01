<?php

namespace App\Http\Controllers;

use App\Events\ProductApprovalStatusChangedEvent;
use App\Http\Requests\AddProductPictureRequest;
use App\Http\Requests\SaveProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\AppSetting;
use App\Models\GeopoliticalZone;
use App\Models\Product;
use App\Models\ProductDeliveryCost;
use App\Models\ProductImage;
use App\Models\VendorPackaging;
use App\Models\ProductSubcategory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VendorDashboardController extends Controller
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
        // Set view based on the role
        $showCategories = false;
        $user_vendor = auth()->user()->vendors()->first();
        $approvalPending = $user_vendor->approval_status == Vendor::STATUS_PENDING;
        $approvalDisapproved = $user_vendor->approval_status == Vendor::STATUS_DISAPPROVED;
        $user_vendor->append('storage_used');
        $user_vendor->append('current_month_orders');
        $storageAllowed = AppSetting::getSetting(AppSetting::VENDOR_STORAGE_LIMIT);
        $numberOfProducts = $user_vendor->products()->count();

        return response()->json(compact(
            'approvalPending', 'approvalDisapproved',
            'showCategories','storageAllowed', 'numberOfProducts'
        ));
    }

}

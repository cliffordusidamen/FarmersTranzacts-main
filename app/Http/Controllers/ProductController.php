<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\VendorPackaging;
use App\Models\ProductSubcategory;
use Illuminate\Http\Request;

class ProductController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // TO DO: Limit list by designation of the signed-in user
        if (authUserIsVendor()) {
            $products = $request->user()->vendorData->products()->paginate(10);
        } else {
            $products = Product::submitted()->paginate(10);
        }

        return response()->json(compact('products'));
    }

    /**
     * Get all parameters needed for rendering product creation form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $vendorData = $request->user()->vendorData;
        $productCategoryList = $vendorData->productCategories->pluck('name', 'id');
        $productCategoryIds = $vendorData->productCategories->pluck('id', 'id');
        $productSubcategories = ProductSubcategory::whereIn('category_id', $productCategoryIds)
            ->get();
        $packagingList = VendorPackaging::pluck('name', 'id');

        return response()->json(compact(
            'productCategoryList', 'productSubcategories', 'packagingList'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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

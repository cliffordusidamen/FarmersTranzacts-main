<?php

namespace App\Http\Controllers;

use App\Events\ProductApprovalStatusChangedEvent;
use App\Http\Requests\AddProductPictureRequest;
use App\Http\Requests\SaveProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\GeopoliticalZone;
use App\Models\Product;
use App\Models\ProductDeliveryCost;
use App\Models\ProductImage;
use App\Models\VendorPackaging;
use App\Models\ProductSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends Controller
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
        if (authUserIsVendor()) {
            $query = $request->user()->vendorData
                ->products()
                ->with(['packaging', 'category', 'subcategory'])
                ->withCount(['images']);
        } else {
            $query = Product::with([
                    'packaging', 'category', 'subcategory',
                    'vendor',
                ]);
        }

        return new ProductCollection($query->orderByDesc('id')->paginate(10));
    }

    /**
     * Get all parameters needed for rendering product creation form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $viewVariables = $this->_getProductFormVariables($request);

        return response()->json($viewVariables);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SaveProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveProductRequest $request)
    {

        $data = $request->only([
            'name',
            'category_id',
            'subcategory_id',
            'description',
            'price',
            'packaging_id',
            'maximum_order_quantity',
        ]);

        $data['submitted_at'] = $request->is_draft ? null : carbon('Y-m-d H:i:s');
        $product = null;

        DB::transaction(function() use (&$product, $request, $data) {

            $product = $request->user()->vendorData->products()->create($data);
            if ($request->picture) {
                $product->addPicture($request->picture);
            }
            collect($request->product_delivery_costs)->each(
                fn($deliveryCost) => $product->deliveryCosts()->create($deliveryCost)
            );
        });

        if (!$product) {
            throw new \Exception('Product not saved! Please, try again.');
        }

        return response()->json([
            'product' => new ProductResource($product),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load([
            'packaging', 'category', 'subcategory',
            'vendor', 'images', 'deliveryCosts',
        ]);
        $this->_abortIfNotVendorProduct($product);
        return response()->json([
            'product' => new ProductResource($product),
        ]);
    }

    /**
     * Get all parameters needed for rendering product edit form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Product $product)
    {
        $this->_abortIfNotVendorProduct($product);
        $product->load(['deliveryCosts']);
        $viewVariables = $this->_getProductFormVariables($request);
        $viewVariables['product'] = new ProductResource($product);

        return response()->json($viewVariables);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SaveProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(SaveProductRequest $request, Product $product)
    {
        $this->_abortIfNotVendorProduct($product);
        
        $saved = false;
        
        DB::transaction(function() use ($product, $request, &$saved) {
            $data = $request->only([
                'name',
                'category_id',
                'subcategory_id',
                'description',
                'price',
                'packaging_id',
                'maximum_order_quantity',
            ]);
    
            $data['submitted_at'] = $request->is_draft ? null : carbon('Y-m-d H:i:s');
            $data['approval_status'] = $request->is_draft ? $product->approval_status : Product::STATUS_PENDING;
            if ($product->forceFill($data)->save()) {
                collect($request->input('product_delivery_costs'))
                    ->each(function($deliveryCost, $i) use ($product) {
                        if (!empty($deliveryCost['id'])) {
                            ProductDeliveryCost::where('id', $deliveryCost['id'])
                                ->update($deliveryCost);
                            return;
                        }
                        $product->deliveryCosts()->create($deliveryCost);
                    });
            }

            $saved = true;


        });

        if (!$saved) {
            throw new \Exception('Product not saved! Please, try again.');
        }

        return response()->json(compact('product'));
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
     * Change vendor approval status
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, Product $product)
    {
        $this->_abortIfNotVendorProduct($product);
        $data = $request->only(['approval_status', 'disapproval_reason']);

        $validator = Validator::make($data, [
            'approval_status' => [
                'required',
                Rule::in(Product::getApprovalStatuses()),
            ],

            'disapproval_reason' => 'sometimes|nullable|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'failed' => true,
            ]);
        }

        if ($product->approval_status == $request->input('approval_status')) {
            return response()->json(compact(
                'product'
            ));
        }

        $product->approval_status = $request->input('approval_status');
        $product->disapproval_reason = $product->approval_status == Product::STATUS_DISAPPROVED
            ? $request->input('disapproval_reason')
            : null;

        if ($product->save()) {
            $accountManager = $product->vendor->users()->first();
            event(new ProductApprovalStatusChangedEvent(
                $product, $accountManager
            ));
            return response()->json(compact(
                'product'
            ));
        }

        return response()->json([
            'failed' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AddProductPictureRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function addPicture(AddProductPictureRequest $request, Product $product)
    {
        $this->_abortIfNotVendorProduct($product);

        $initialPictureCount = $product->images()->count();
        if ($initialPictureCount >= 5) {
            throw new \Exception('Maximum of 5 pictures allowed per product!');
        }

        $product->addPicture($request->picture);

        if ($initialPictureCount >= $product->images()->count()) {
            throw new \Exception('Product image not saved! Please, try again.');
        }
        $product->load(['images']);

        return response()->json([
            'product' => new ProductResource($product),
        ]);
    }

    /**
     * Set product main/feature picture
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @param  \App\Models\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function setFeaturePicture(
        Request $request,
        Product $product,
        ProductImage $productImage
    ) {
        abort_if(
            $productImage->product_id != $product->id,
            'Invalid product image selected for setting as featured!'
        );

        $isFeatured = false;

        DB::transaction(function() use (&$isFeatured, $productImage, $product) {
            $featureImage = $product->featureImage();
            if ($featureImage) {
                $featureImage->is_featured = false;
                $featureImage->save();
            }
            $productImage->is_featured = true;
            $productImage->save();
            $isFeatured = true;
        });

        if (!$isFeatured) {
            throw new \Exception('Selected picture was not set as main picture! Please, try again.');
        }

        return response()->json();
    }

    /**
     * Delete a product picture
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @param  \App\Models\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function deletePicture(
        Request $request,
        Product $product,
        ProductImage $productImage
    ) {
        abort_if(
            $productImage->product_id != $product->id,
            'Invalid product image selected for deletion!'
        );

        $deleted = false;

        DB::transaction(function() use (&$deleted, $productImage, $product) {
            if (
                !$productImage->delete()
                || !Storage::exists($productImage->path)
            ) {
                return;
            }
            if (
                $productImage->is_featured
                && $product->images()->count()
            ) {
                $product->images()->first()->forceFill([
                    'is_featured' => true,
                ])
                ->save();
            }
            if (!Storage::delete($productImage->path)) {
                return;
            }
            $deleted = true;
        });

        if (!$deleted) {
            throw new \Exception('Image was not deleted! Please, try again.');
        }

        return response()->json();
    }

    /**
     * Prepare variables for rendering product form
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function _getProductFormVariables($request)
    {
        $vendorData = $request->user()->vendorData;
        $productCategoryList = $vendorData->productCategories->pluck('name', 'id');
        $productCategoryIds = $vendorData->productCategories->pluck('id', 'id');
        $productSubcategories = ProductSubcategory::whereIn('category_id', $productCategoryIds)
            ->get();
        $packagingList = $vendorData->packagings()->pluck('name', 'id');
        $zones = GeopoliticalZone::with('states')->get();

        return compact(
            'productCategoryList', 'productSubcategories', 'packagingList',
            'zones',
        );
    }

    /**
     * Prevent access to product if it does not belong to the current user
     * 
     * @throws \Exception
     * @return void
     */
    public function _abortIfNotVendorProduct(Product $product)
    {
        if (authUserIsVendor()) {
            abort_if(
                $product->vendor_id != request()->user()->vendorData->id,
                404
            );
        }
    }
}

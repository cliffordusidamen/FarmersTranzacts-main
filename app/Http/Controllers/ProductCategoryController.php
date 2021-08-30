<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductCategoryController extends Controller
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
        $productCategories = ProductCategory::withCount(['products', 'subcategories'])
            ->orderByDesc('id')
            ->get();

        return response()->json(compact('productCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['name', 'description', 'is_active']);
        Validator::make($data, ProductCategory::validationRules())->validate();

        $product_category = ProductCategory::create($data);

        if (!$product_category) {
            throw ValidationException::withMessages([
                'name' => 'product category not saved! Please, try again.',
            ]);
        }

        return response()->json(compact('product_category'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product_category = ProductCategory::withCount(['subcategories'])
            ->withCount(['products'])
            ->findOrFail((int) $id);
        
        if ($product_category->subcategories->count()) {
            $product_category->subcategories->map(fn ($subCategory, $key) => $subCategory->loadCount('products'));
        }

        return response()->json(compact('product_category'));
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
        $product_category = ProductCategory::findOrFail((int) $id);
        $data = $request->only(['name', 'description', 'is_active']);
        Validator::make($data, ProductCategory::validationRules((int) $id))->validate();

        $product_category->forceFill($data);

        if (!$product_category->save()) {
            return response()->json([
                'save_failed' => true,
                'message' => 'Product category not saved! Please, try again.'
            ]);
        }

        return response()->json(compact('product_category'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = ProductCategory::find((int) $id);

        if (!$category) {
            return response()->json();
        }

        if ($category->products()->count()) {
            throw new \Exception('A product category that has products cannot be deleted. You may only deactivate it!');
        }
        
        if (!$category->delete()) {
            throw new \Exception('Product category not deleted. Please, try again!');
        }

        return response()->json([
            'success' => true,
            'message' => 'Product category deleted!'
        ], 200);
    }

    /**
     * Store a newly created subcategory to database
     * 
     * @param  int  $productCategoryId
     * @return \Illuminate\Http\Response
     */
    public function storeSubcategory(Request $request, $productCategoryId)
    {
        $product_category = ProductCategory::findOrFail((int) $productCategoryId);
        $data = $request->only(['name', 'description', 'is_active']);
        Validator::make($data, ProductSubcategory::validationRules())->validate();

        $product_subcategory = $product_category->subcategories()->create($data);

        if (!$product_subcategory) {
            throw ValidationException::withMessages([
                'name' => 'Product subcategory not saved! Please, try again.',
            ]);
        }

        return response()->json(compact('product_subcategory'));

    }

    /**
     * Update subcategory details
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $category_id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSubcategory(Request $request, $category_id, $id)
    {
        $product_subcategory = ProductSubcategory::with('category')
            ->where('id', (int) $id)
            ->where('category_id', (int) $category_id)
            ->firstOrFail();

        $data = $request->only(['name', 'description']);
        $data = Validator::make($data, ProductSubcategory::validationRules((int) $id))->validate();

        $product_subcategory->forceFill($data);

        if (!$product_subcategory->save()) {
            return response()->json([
                'save_failed' => true,
                'message' => 'Product category not saved! Please, try again.'
            ]);
        }

        return response()->json(compact('product_subcategory'));
    }
}

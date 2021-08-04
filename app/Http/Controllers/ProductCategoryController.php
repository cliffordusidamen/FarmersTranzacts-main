<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
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
        $this->middleware('auth:sanctum');
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

        // if ($productCategories->count()) {
        //     $productCategories->map(function($department, $deptKey) {
        //         $department->sections->map(fn ($section, $key) => $section->loadCount('products'));
        //     });
        // }

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
        //
    }
}

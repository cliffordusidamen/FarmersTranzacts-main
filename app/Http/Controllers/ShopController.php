<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    /**
     * Create instance
     * 
     */
    public function __construct()
    { }

    /**
     * List all products, uncategorized
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get products that have been approved and are makrd as available by the vendors
        $products = \App\Models\Product::with([
                'category', 'images', 'vendor', 'packaging',
            ])
            ->availableForSale()
            ->paginate(12);

        return Inertia::render('Shop/ProductsList', compact('products'));
    }

    /**
     * List all products, categorized
     * 
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function categorized($slug)
    {
        $productCategory = ProductCategory::where('slug', $slug)
            ->firstOrFail();
    
        $products = $productCategory
            ->products()
            ->with([
                'category', 'images', 'vendor', 'packaging',
            ])
            ->availableForSale()
            ->paginate(12);

        return Inertia::render('Shop/ProductsList', compact(
            'products', 'productCategory'
        ));
    }

    /**
     * List all products, subcategorized
     * 
     * @param  string  $categorySlug
     * @param  string  $subcategorySlug
     * @return \Illuminate\Http\Response
     */
    public function subcategorized($categorySlug, $subcategorySlug)
    {
        $productCategory = ProductCategory::where('slug', $categorySlug)
            ->firstOrFail();

        $subcategory = $productCategory->subcategories()
            ->where('slug', $subcategorySlug)
            ->firstOrFail();
    
        $products = $subcategory
            ->products()
            ->with([
                'category', 'images', 'vendor', 'packaging',
            ])
            ->availableForSale()
            ->paginate(12);

        return Inertia::render('Shop/ProductsList', compact(
            'products', 'productCategory', 'subcategory'
        ));
    }

    /**
     * View product details
     * 
     * @param  string  $slug
     */
    public function showProduct($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'subcategory', 'vendor', 'packaging'])
            ->firstOrFail();

        return Inertia::render('Shop/ShowProduct.vue', compact('product'));
    }
}

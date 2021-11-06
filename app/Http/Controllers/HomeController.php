<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCategoryCollection;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $products = Product::with([
            'category',
            'subcategory',
            'packaging',
        ])
            ->availableForSale()
            ->inStock()
            ->inRandomOrder()
            ->take(8)
            ->get();

        $categoriesWithDisplayPicture = ProductCategory::hasDisplayPicture()
            ->withCount('products')
            ->inRandomOrder()
            ->get();

        return Inertia::render('Home', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'showMenu' => false,
            'products' => $products,
            'categoriesWithDisplayPicture'
                => new ProductCategoryCollection($categoriesWithDisplayPicture),
        ]);
    }
}

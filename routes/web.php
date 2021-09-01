<?php

use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

/*
 |-----------------------------------------------------------------------------
 | Registration routes
 |-----------------------------------------------------------------------------
 */
Route::middleware(['guest'])->prefix('registration')->name('registration.')->group(function() {

    Route::get('shopper', [\App\Http\Controllers\RegistrationController::class, 'showShopperForm'])->name('shopper');
    Route::post('shopper', [\App\Http\Controllers\RegistrationController::class, 'saveShopper'])->name('save_shopper');

    Route::get('vendor', [\App\Http\Controllers\RegistrationController::class, 'showVendorForm'])->name('vendor');
    Route::post('vendor', [\App\Http\Controllers\RegistrationController::class, 'saveVendor'])->name('save_vendor');
});


Route::get('dashboard', \App\Http\Controllers\DashboardController::class)
    ->name('dashboard')
    ->middleware(['auth:sanctum', 'verified']);

Route::middleware(['auth:sanctum', 'verified'])->prefix('categories')->name('categories.')->group(function() {

    Route::get('/', fn () => Inertia::render('Admin/CategoriesList'))->name('index');

    Route::get('create', fn() => Inertia::render('Admin/CreateCategory'))->name('create');

    Route::get('show/{category}', function(ProductCategory $category) {
        $subcategories = $category->subcategories;
        return Inertia::render('Admin/ShowCategory', compact('category', 'subcategories'));
    })->name('show');

    Route::get(
            'edit/{category}',
            fn(ProductCategory $category)
                => Inertia::render('Admin/CreateCategory', compact('category'))
        )->name('edit');

    Route::post('save/{id?}', \App\Actions\ProductCategories\SaveCategoryAction::class)->name('save');

    Route::get(
            '{category}/subcategory/create',
            fn(ProductCategory $category)
                => Inertia::render('Admin/CreateSubcategory', compact('category'))
        )
        ->name('create_subcategory');

    Route::get(
            '{category}/subcategory/edit/{subcategory}',
            fn(ProductCategory $category, ProductSubcategory $subcategory)
                => Inertia::render('Admin/CreateSubcategory', compact('category', 'subcategory'))
        )
        ->name('edit_subcategory');

    Route::post(
            '{category}/subcategory/{subcategory?}',
            \App\Actions\ProductCategories\SaveSubcategoryAction::class
        )
        ->name('save_subcategory');
});


// SHOP ROUTES
Route::prefix('shop')->name('shop.')->group(function() {
    Route::get('/', function() {
        $products = \App\Models\Product::with(['category', 'images', 'store'])->paginate(12);

        // dd($products);

        return Inertia::render('Shop/ProductsList', compact('products'));
    })->name('index');
});

/*
 |-----------------------------------------------------------------------------
 | Vendors routes
 |-----------------------------------------------------------------------------
 */
Route::prefix('vendors')->name('vendors.')->group(function() {
    Route::post('changeStatus/{vendor}', [\App\Http\Controllers\VendorController::class, 'changeStatus'])->name('change_status');
});
Route::resource('vendors', \App\Http\Controllers\VendorController::class);

/*
 |-----------------------------------------------------------------------------
 | Profile routes
 |-----------------------------------------------------------------------------
 */
Route::prefix('profile')->name('profile.')->group(function() {
    Route::get('vendor-details', [\App\Http\Controllers\ProfileController::class, 'showEditVendorForm'])->name('edit_vendor_details');
    Route::get('edit-vendor-disapproval', [\App\Http\Controllers\ProfileController::class, 'showEditDisapprovedVendorForm'])->name('edit_vendor_disapproval');
    Route::post('update-vendor-profile', [\App\Http\Controllers\ProfileController::class, 'saveDisapprovedVendor'])->name('update_vendor_profile');
    Route::delete('destroy-vendor-logo', [\App\Http\Controllers\ProfileController::class, 'destroyVendorLogo'])->name('destroy_vendor_logo');
});


Route::get('/abc', function() {
    $record = \App\Models\User::has('vendors')
        ->with(['unapprovedVendors'])
        ->paginate();

    return response()->json($record);

    dd($record);
});

Route::get('/test-mail', function() {
    \App\Models\User::find(2)->sendEmailVerificationNotification();
});
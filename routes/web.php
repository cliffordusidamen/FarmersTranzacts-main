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

});


/*
 |-----------------------------------------------------------------------------
 | Shop routes
 |-----------------------------------------------------------------------------
 */
Route::prefix('shop')->name('shop.')->group(function() {
    Route::get('/', [\App\Http\Controllers\ShopController::class, 'index'])->name('index');
    Route::get('{categorySlug}/{subcategorySlug}', [\App\Http\Controllers\ShopController::class, 'subcategorized'])->name('subcategorized');
    Route::get('{slug}', [\App\Http\Controllers\ShopController::class, 'categorized'])->name('categorized');
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

/*
 |-----------------------------------------------------------------------------
 | Shopping cart routes
 |-----------------------------------------------------------------------------
 */
Route::prefix('shopping-cart')->name('shopping_cart.')->group(function() {
    Route::get('/', [\App\Http\Controllers\ShoppingCartController::class, 'view'])->name('view');
    Route::post('/', [\App\Http\Controllers\ShoppingCartController::class, 'addItem'])->name('add_item');
    Route::post('remove-item', [\App\Http\Controllers\ShoppingCartController::class, 'removeItem'])->name('remove_item');
    Route::post('update-items', [\App\Http\Controllers\ShoppingCartController::class, 'updateCartItems'])->name('update_all');
    Route::get('checkout', [\App\Http\Controllers\ShoppingCartController::class, 'checkout'])->name('checkout');
    Route::post('order', [\App\Http\Controllers\ShoppingCartController::class, 'order'])->name('order');
});

Route::post('/logout', function(\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Log::info('In Here');
    $cart = $request->session()->get('cart', ['items', []]);
    
    \Illuminate\Support\Facades\Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    $request->session()->put('cart', $cart);
    return redirect('/');
})->name('logout');
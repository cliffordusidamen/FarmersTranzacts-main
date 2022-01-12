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

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

/*
 |-----------------------------------------------------------------------------
 | Registration routes
 |-----------------------------------------------------------------------------
 */
Route::middleware(['guest'])->prefix('registration')->name('registration.')->group(function() {

    Route::get('shopper', [\App\Http\Controllers\RegistrationController::class, 'showShopperForm'])->name('shopper');
    Route::post('shopper', [\App\Http\Controllers\RegistrationController::class, 'saveShopper'])->name('save_shopper');

    Route::get('agent', [\App\Http\Controllers\RegistrationController::class, 'showAgentForm'])->name('agent');
    Route::post('agent', [\App\Http\Controllers\RegistrationController::class, 'saveAgent'])->name('save_agent');
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
    Route::get('product/{slug}', [\App\Http\Controllers\ShopController::class, 'showProduct'])->name('show_product');
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
// Route::resource('vendors', \App\Http\Controllers\VendorController::class);

/*
 |-----------------------------------------------------------------------------
 | Profile routes
 |-----------------------------------------------------------------------------
 */
Route::prefix('profile')->name('profile.')->group(function() {
    Route::get('unverified-profile', [\App\Http\Controllers\ProfileController::class, 'showUnverifiedProfile'])->name('unverified_profile');
    Route::get('agent-details', [\App\Http\Controllers\ProfileController::class, 'showEditAgentForm'])->name('edit_agent_details');
    Route::get('edit-agent-disapproval', [\App\Http\Controllers\ProfileController::class, 'showEditDisapprovedAgentForm'])->name('edit_agent_disapproval');
    Route::post('edit-agent-disapproval', [\App\Http\Controllers\ProfileController::class, 'resubmitForApproval'])->name('resubmit_for_approval');
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
    Route::post('set-delivery-state', [\App\Http\Controllers\ShoppingCartController::class, 'setDeliveryState'])->name('set_delivery_state');
    Route::get('checkout', [\App\Http\Controllers\ShoppingCartController::class, 'checkout'])->name('checkout');
    Route::post('order', [\App\Http\Controllers\ShoppingCartController::class, 'order'])->name('order');
    Route::get('payment-verify', [\App\Http\Controllers\ShoppingCartController::class, 'paymentVerify'])->name('payment_callback');
    Route::get('payment-cancel/{order}', [\App\Http\Controllers\ShoppingCartController::class, 'paymentCancel'])->name('cancel_payment');
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


/*
 |-----------------------------------------------------------------------------
 | Public pages routes
 |-----------------------------------------------------------------------------
 */
Route::get('about-us', [\App\Http\Controllers\PageController::class, 'about'])
    ->name('about_us');

Route::get('terms-of-use', [\App\Http\Controllers\PageController::class, 'terms'])
    ->name('terms');

Route::get('privacy-policy', [\App\Http\Controllers\PageController::class, 'privacyPolicy'])
    ->name('privacy_policy');

Route::get('terms-of-use', [\App\Http\Controllers\PageController::class, 'terms'])
    ->name('terms');

Route::get('/contact-us', fn () => Inertia::render('ContactUs.vue', [
        'showMenu' => false,
    ]))
    ->name('contact_us');

Route::post('/contact-us', [\App\Http\Controllers\PageController::class, 'sendContactUs'])
    ->name('send_contact_us');

Route::get('/become-an-agent', [\App\Http\Controllers\PageController::class, 'becomeAnAgent'])
    ->name('become_an_agent');
/*
 |-----------------------------------------------------------------------------
 | Vendor mini-web routes
 |-----------------------------------------------------------------------------
 */
Route::get('/page/{slug}', [\App\Http\Controllers\PageController::class, 'vendorPage'])
    ->name('vendor_page');


/*
|-----------------------------------------------------------------------------
| Shoppers personal orders routes
|-----------------------------------------------------------------------------
*/
Route::prefix('my-orders')->name('my_orders.')->group(function() {
    Route::get('/', [\App\Http\Controllers\MyOrderController::class, 'index'])->name('index');
    Route::get('/{order}', [\App\Http\Controllers\MyOrderController::class, 'show'])->name('show');
    Route::get('/{vendor_order}/cancel-vendor-order', [\App\Http\Controllers\MyOrderController::class, 'showCancelVendorOrder'])->name('show_cancel_vendor_order');
    Route::post('/{vendor_order}/cancel-vendor-order', [\App\Http\Controllers\MyOrderController::class, 'cancelVendorOrder'])->name('cancel_vendor_order');
    Route::post('/{vendor_order}/review', [\App\Http\Controllers\MyOrderController::class, 'reviewVendorOrder'])->name('review_vendor_order');
});

/*
|-----------------------------------------------------------------------------
| Help center routes
|-----------------------------------------------------------------------------
*/
Route::prefix('help')->name('help.')->group(function() {
    Route::get('/', [\App\Http\Controllers\HelpController::class, 'index'])->name('index');
    Route::get('/faq', [\App\Http\Controllers\HelpController::class, 'faq'])->name('faq');
    Route::get('{slug}', [\App\Http\Controllers\HelpController::class, 'page'])->name('page');
});
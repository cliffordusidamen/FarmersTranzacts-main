<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
|-------------------------------------------------------------------------
| Users routes
|-------------------------------------------------------------------------
*/
Route::prefix('users')->group(function() {
    Route::get('shopper-info/{id}', [\App\Http\Controllers\UserController::class, 'showAgentOrShopper']);
    Route::get('staff-info/{id}', [\App\Http\Controllers\UserController::class, 'showStaff']);
    Route::get('seller-info/{id}', [\App\Http\Controllers\UserController::class, 'showSeller']);
    Route::get('/{id}/orders', [\App\Http\Controllers\UserController::class, 'userOrders']);
    Route::get('sellers', [\App\Http\Controllers\UserController::class, 'sellers']);
    Route::get('list/{designation}', [\App\Http\Controllers\UserController::class, 'list']);
    Route::get('edit-staff/{id}', [\App\Http\Controllers\UserController::class, 'editStaff']);
    Route::post('/store-staff', [\App\Http\Controllers\UserController::class, 'storeStaff']);
    Route::get('add-staff', [\App\Http\Controllers\UserController::class, 'addStaff']);
    Route::post('/update-staff/{staff}', [\App\Http\Controllers\UserController::class, 'updateStaff']);
    Route::post('/change-agent-status/{id}', [\App\Http\Controllers\AgentController::class, 'changeStatus']);
});
Route::apiResource('users', \App\Http\Controllers\UserController::class)->only([
    'show', 'update', 'destroy',
]);



/*
|-------------------------------------------------------------------------
| Guest route
|-------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'frontend'])->get('/user', function (Request $request) {
    return response()->json();
});
Route::middleware(['frontend'])->post('/login', [\App\Http\Controllers\AuthFrontendController::class, 'login']);
Route::middleware(['auth:sanctum'])->post('/logout', [\App\Http\Controllers\AuthFrontendController::class, 'logout']);

/*
|-------------------------------------------------------------------------
| Dashboard route
|-------------------------------------------------------------------------
*/
Route::get('dashboard', \App\Http\Controllers\DashboardController::class);


/*
|-------------------------------------------------------------------------
| Departments routes
|-------------------------------------------------------------------------
*/
Route::prefix('departments')->group(function() {
    Route::get('/', [\App\Http\Controllers\DepartmentController::class, 'index']);
    Route::post('store', [\App\Http\Controllers\DepartmentController::class, 'store']);
    Route::delete('{id}', [\App\Http\Controllers\DepartmentController::class, 'destroy']);
    Route::get('show/{id}', [\App\Http\Controllers\DepartmentController::class, 'show']);
    Route::put('update/{id}', [\App\Http\Controllers\DepartmentController::class, 'update']);
    Route::post('{id}/store-section', [\App\Http\Controllers\DepartmentController::class, 'storeSection']);
    Route::post('{department_id}/update-section/{section_id}', [\App\Http\Controllers\DepartmentController::class, 'updateSection']);
});

/*
|-------------------------------------------------------------------------
| Product categories routes
|-------------------------------------------------------------------------
*/
Route::prefix('product_categories')->group(function() {
    Route::post('{id}/store-subcategory', [\App\Http\Controllers\ProductCategoryController::class, 'storeSubcategory']);
    Route::put('{category_id}/update-subcategory/{id}', [\App\Http\Controllers\ProductCategoryController::class, 'updateSubcategory']);
});
Route::apiResource('product_categories', \App\Http\Controllers\ProductCategoryController::class);


/*
|-------------------------------------------------------------------------
| Product categories routes
|-------------------------------------------------------------------------
*/
Route::prefix('roles')->group(function() {
    Route::get('permissions', [\App\Http\Controllers\RoleController::class, 'permissions']);
});
Route::apiResource('roles', \App\Http\Controllers\RoleController::class);



/*
|-------------------------------------------------------------------------
| Model fetcher:
| This is used to fetch multiple model entities at once, linke GraphQL
|-------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->post('/get-model-data', \App\Http\Controllers\ModelFetcherController::class);

Route::get('/test-model-list', function () {
    initModelPermissions();

    response()->json(getModelNames());
});


/*
|-------------------------------------------------------------------------
| Vendors routes
|-------------------------------------------------------------------------
*/
Route::prefix('vendors')->middleware(['auth:sanctum', 'frontend'])->group(function() {
    Route::post('changeStatus/{vendor}', [\App\Http\Controllers\VendorController::class, 'changeStatus']);
    Route::get('edit-vendor-disapproval', [\App\Http\Controllers\ProfileController::class, 'showEditDisapprovedVendorForm']);
    Route::post('save-vendor-disapproval', [\App\Http\Controllers\ProfileController::class, 'saveDisapprovedVendor']);
});
Route::apiResource('vendors', \App\Http\Controllers\VendorController::class)->middleware(['auth:sanctum', 'frontend']);

/*
 |-----------------------------------------------------------------------------
 | Registration routes
 |-----------------------------------------------------------------------------
 */
Route::middleware(['guest'])->prefix('registration')->group(function() {

    Route::get('shopper', [\App\Http\Controllers\RegistrationController::class, 'showShopperForm']);
    Route::post('shopper', [\App\Http\Controllers\RegistrationController::class, 'saveShopper']);

    Route::get('vendor', [\App\Http\Controllers\RegistrationController::class, 'showVendorForm']);
    Route::post('vendor', [\App\Http\Controllers\RegistrationController::class, 'saveVendor']);
});




/*
 |-----------------------------------------------------------------------------
 | Resend Email Verification ROutes
 |-----------------------------------------------------------------------------
 */
Route::middleware(['auth:sanctum', 'throttle:6,1', 'frontend'])
    ->post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return response()->json();
    });

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return response()->json([], 202);
})->middleware(['auth:sanctum', 'frontend']);


/*
|-------------------------------------------------------------------------
| Product routes
|-------------------------------------------------------------------------
*/
Route::prefix('products')->middleware(['auth:sanctum', 'frontend'])->group(function() {
    Route::post('changeStatus/{product}', [\App\Http\Controllers\ProductController::class, 'changeStatus']);
    Route::post('{product}/add-picture', [\App\Http\Controllers\ProductController::class, 'addPicture']);
    Route::delete('{product}/delete-picture/{productImage}', [\App\Http\Controllers\ProductController::class, 'deletePicture']);
    Route::post('{product}/set-featured-picture/{productImage}', [\App\Http\Controllers\ProductController::class, 'setFeaturePicture']);
});
Route::resource('products', \App\Http\Controllers\ProductController::class);


/*
|-------------------------------------------------------------------------
| Packagings routes
|-------------------------------------------------------------------------
*/
Route::resource('packagings', \App\Http\Controllers\VendorPackagingController::class);


/*
|-------------------------------------------------------------------------
| Setup routes
|-------------------------------------------------------------------------
*/
Route::prefix('setup')->middleware(['auth:sanctum', 'frontend'])->group(function() {
    Route::get('settings', [\App\Http\Controllers\SetupController::class, 'settings']);
    Route::post('store-settings', [\App\Http\Controllers\SetupController::class, 'storeSettings']);
    Route::get('pages', [\App\Http\Controllers\SetupController::class, 'pages']);
    Route::get('edit-page/{id}', [\App\Http\Controllers\SetupController::class, 'editPage']);
    Route::put('update-page/{id}', [\App\Http\Controllers\SetupController::class, 'updatePage']);
    Route::get('banners', [\App\Http\Controllers\SetupController::class, 'banners']);
    Route::post('create-banner', [\App\Http\Controllers\SetupController::class, 'createBanner']);
    Route::get('edit-banner/{id}', [\App\Http\Controllers\SetupController::class, 'editBanner']);
    Route::post('edit-banner/{id}', [\App\Http\Controllers\SetupController::class, 'updateBanner']);
    Route::delete('delete-banner/{id}', [\App\Http\Controllers\SetupController::class, 'deleteBanner']);

    Route::get('agent-commissions', [\App\Http\Controllers\SetupController::class, 'agentCommissions']);
    Route::post('agent-commissions', [\App\Http\Controllers\SetupController::class, 'storeAgentCommissions']);
    Route::get('edit-agent-commissions/{id}', [\App\Http\Controllers\SetupController::class, 'editAgentCommissions']);
    Route::put('update-agent-commissions/{id}', [\App\Http\Controllers\SetupController::class, 'updateAgentCommissions']);
    Route::delete('delete-agent-commission/{id}', [\App\Http\Controllers\SetupController::class, 'deleteAgentCommission']);

    Route::get('sales-commissions', [\App\Http\Controllers\SetupController::class, 'salesCommissions']);
    Route::post('sales-commissions', [\App\Http\Controllers\SetupController::class, 'storeSalesCommissions']);
    Route::get('edit-sales-commissions/{id}', [\App\Http\Controllers\SetupController::class, 'editSalesCommissions']);
    Route::put('update-sales-commissions/{id}', [\App\Http\Controllers\SetupController::class, 'updateSalesCommissions']);
    Route::delete('delete-sales-commission/{id}', [\App\Http\Controllers\SetupController::class, 'deleteSalesCommission']);
});



/*
|-------------------------------------------------------------------------
| Vendor orders routes (for seller app)
|-------------------------------------------------------------------------
*/
Route::prefix('vendor_orders')->group(function() {
    Route::put('{id}/transit', [\App\Http\Controllers\VendorOrderController::class, 'transit']);
    Route::put('{id}/delivery', [\App\Http\Controllers\VendorOrderController::class, 'delivery']);
});
Route::resource('vendor_orders', \App\Http\Controllers\VendorOrderController::class);


/*
|-------------------------------------------------------------------------
| Orders routes (for admin app)
|-------------------------------------------------------------------------
*/
Route::resource('orders', \App\Http\Controllers\OrderController::class);


/*
|-------------------------------------------------------------------------
| Business info routes (for seller app)
|-------------------------------------------------------------------------
*/
Route::prefix('business-info')->group(function() {
    Route::get('/', [\App\Http\Controllers\BusinessInfoController::class, 'index']);
    Route::post('update-logo', [\App\Http\Controllers\BusinessInfoController::class, 'updateLogo']);
    Route::post('update-cover-picture', [\App\Http\Controllers\BusinessInfoController::class, 'updateCoverPicture']);
    Route::post('update-about', [\App\Http\Controllers\BusinessInfoController::class, 'updateAbout']);
});

/*
 |-----------------------------------------------------------------------------
 | Profile routes
 |-----------------------------------------------------------------------------
 */
Route::prefix('profile')->group(function() {
    Route::post('/change-password', [\App\Http\Controllers\UserController::class, 'changePassword']);
    Route::post('/update', [\App\Http\Controllers\UserController::class, 'updateProfile']);
});
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
Route::prefix('departments')->name('departments.')->group(function() {
    Route::get('/', [\App\Http\Controllers\DepartmentController::class, 'index'])->name('index');
    Route::post('store', [\App\Http\Controllers\DepartmentController::class, 'store'])->name('store');
    Route::delete('{id}', [\App\Http\Controllers\DepartmentController::class, 'destroy'])->name('destroy');
    Route::get('show/{id}', [\App\Http\Controllers\DepartmentController::class, 'show'])->name('show');
    Route::put('update/{id}', [\App\Http\Controllers\DepartmentController::class, 'update'])->name('update');
    Route::post('{id}/store-section', [\App\Http\Controllers\DepartmentController::class, 'storeSection'])->name('store_section');
    Route::post('{department_id}/update-section/{section_id}', [\App\Http\Controllers\DepartmentController::class, 'updateSection'])->name('update_section');
});

/*
|-------------------------------------------------------------------------
| Product categories routes
|-------------------------------------------------------------------------
*/
Route::prefix('product_categories')->name('product_categories.')->group(function() {
    Route::post('{id}/store-subcategory', [\App\Http\Controllers\ProductCategoryController::class, 'storeSubcategory'])->name('store_subcategory');
    Route::put('{category_id}/update-subcategory/{id}', [\App\Http\Controllers\ProductCategoryController::class, 'updateSubcategory'])->name('update_subcategory');
});
Route::apiResource('product_categories', \App\Http\Controllers\ProductCategoryController::class);


/*
|-------------------------------------------------------------------------
| Product categories routes
|-------------------------------------------------------------------------
*/
Route::prefix('roles')->name('roles.')->group(function() {
    Route::get('permissions', [\App\Http\Controllers\RoleController::class, 'permissions']);
});
Route::apiResource('roles', \App\Http\Controllers\RoleController::class);


/*
|-------------------------------------------------------------------------
| Users routes
|-------------------------------------------------------------------------
*/
Route::prefix('users')->name('users.')->group(function() {
    Route::get('sellers', [\App\Http\Controllers\UserController::class, 'sellers']);
    Route::get('list/{designation}', [\App\Http\Controllers\UserController::class, 'list']);
    Route::get('edit-staff/{id}', [\App\Http\Controllers\UserController::class, 'editStaff']);
    Route::post('/store-staff', [\App\Http\Controllers\UserController::class, 'storeStaff']);
    Route::get('add-staff', [\App\Http\Controllers\UserController::class, 'addStaff']);
    Route::post('/update-staff/{staff}', [\App\Http\Controllers\UserController::class, 'updateStaff']);
});
Route::apiResource('users', \App\Http\Controllers\UserController::class)->only([
    'show', 'update', 'destroy',
]);


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
Route::prefix('vendors')->name('vendors.')->middleware(['auth:sanctum', 'frontend'])->group(function() {
    Route::post('changeStatus/{vendor}', [\App\Http\Controllers\VendorController::class, 'changeStatus']);
    Route::get('edit-vendor-disapproval', [\App\Http\Controllers\ProfileController::class, 'showEditDisapprovedVendorForm']);
    Route::post('save-vendor-disapproval', [\App\Http\Controllers\ProfileController::class, 'saveDisapprovedVendor'])->name('update_vendor_profile');
});
Route::apiResource('vendors', \App\Http\Controllers\VendorController::class)->middleware(['auth:sanctum', 'frontend']);

/*
 |-----------------------------------------------------------------------------
 | Registration routes
 |-----------------------------------------------------------------------------
 */
Route::middleware(['guest'])->prefix('registration')->name('registration.')->group(function() {

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
Route::prefix('products')->name('products.')->middleware(['auth:sanctum', 'frontend'])->group(function() {
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
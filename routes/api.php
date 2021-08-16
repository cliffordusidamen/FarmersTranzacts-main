<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();
    $user->profile_photo_url;
    return $user;
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    if (auth()->check()) {

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }

    return response()->json();
});

/*
|-------------------------------------------------------------------------
| Dashboard route
|-------------------------------------------------------------------------
*/
Route::get('dashboard', \App\Http\Controllers\DashboardController::class)
    ->name('dashboard')
    ->middleware(['auth:sanctum', 'admin_app']);


/*
|-------------------------------------------------------------------------
| Departments routes
|-------------------------------------------------------------------------
*/
Route::prefix('departments')->name('departments.')->group(function() {
    Route::get('/', [\App\Http\Controllers\DepartmentController::class, 'index'])->name('index');
    Route::post('store', [\App\Http\Controllers\DepartmentController::class, 'store'])->name('store');
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
    Route::get('list/{designation}', [\App\Http\Controllers\UserController::class, 'list']);
    Route::post('/store-staff', [\App\Http\Controllers\UserController::class, 'storeStaff']);
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
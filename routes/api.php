<?php

use Illuminate\Http\Request;
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
    auth()->logout();
    if (auth()->check()) {

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }

    return response()->json();
});

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
// Route::prefix('product_categories')->name('product_categories.')->group(function() {
    // Route::get('/', [\App\Http\Controllers\ProductCategoryController::class, 'index'])->name('index');
    // Route::post('store', [\App\Http\Controllers\DepartmentController::class, 'store'])->name('store');
    // Route::get('show/{id}', [\App\Http\Controllers\DepartmentController::class, 'show'])->name('show');
    // Route::put('update/{id}', [\App\Http\Controllers\DepartmentController::class, 'update'])->name('update');
    // Route::post('{id}/store-section', [\App\Http\Controllers\DepartmentController::class, 'storeSection'])->name('store_section');
    // Route::post('{department_id}/update-section/{section_id}', [\App\Http\Controllers\DepartmentController::class, 'updateSection'])->name('update_section');
// });
Route::apiResource('product_categories', \App\Http\Controllers\ProductCategoryController::class);


/*
|-------------------------------------------------------------------------
| Model fetcher:
| This is used to fetch multiple model entities at once, linke GraphQL
|-------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->post('/get-model-data', \App\Http\Controllers\ModelFetcherController::class);

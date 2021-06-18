<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\Catalogue\CatalogueController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\ColorProduct\ColorProductController;
use App\Http\Controllers\Admin\SizeProduct\SizeProductController;
use App\Http\Controllers\Admin\SubCategory\SubCategoryController;
use App\Http\Controllers\Admin\Dataset\DatasetController;
use App\Http\Controllers\Admin\Material\MaterialController;
use App\Http\Controllers\Admin\Product\ProductController;
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

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::group(['middleware' => ['auth:api', 'role:superadmin']], function () {
        Route::get('validate-token', [AuthController::class, 'validateToken']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('data', [AuthController::class, 'getUser']);
        /* DATASET API */
        Route::group(['prefix' => 'dataset'], function () {
            Route::get('category', [DatasetController::class, 'categoryDataset']);
            Route::get('sub-category', [DatasetController::class, 'subCategoryDataset']);
            Route::get('material', [DatasetController::class, 'materialDataset']);
            Route::get('catalogue', [DatasetController::class, 'catalogueDataset']);
            Route::get('size', [DatasetController::class, 'sizeDataset']);
            Route::get('color', [DatasetController::class, 'colorDataset']);
        });
        /* END /*

        /* CATEGORY ROUTE */
        Route::group(['prefix' => 'category'], function () {
            Route::get('', [CategoryController::class, 'index']);
            Route::post('', [CategoryController::class, 'store']);
            Route::get('{id}', [CategoryController::class, 'show']);
            Route::post('{id}', [CategoryController::class, 'update']);
            Route::delete('{id}', [CategoryController::class, 'destroy']);
            Route::patch('{id}', [CategoryController::class, 'restore']);
        });
        /* END */

        /* SUB CATEGORY ROUTE */
        Route::group(['prefix' => 'subcategory'], function () {
            Route::get('', [SubCategoryController::class, 'index']);
            Route::post('', [SubCategoryController::class, 'store']);
            Route::get('{id}', [SubCategoryController::class, 'show']);
            Route::post('{id}', [SubCategoryController::class, 'update']);
            Route::delete('{id}', [SubCategoryController::class, 'destroy']);
            Route::patch('{id}', [SubCategoryController::class, 'restore']);
        });
        /* END */

        /* CATALOGUE ROUTE */
        Route::group(['prefix' => 'catalogue'], function () {
            Route::get('', [CatalogueController::class, 'index']);
            Route::post('', [CatalogueController::class, 'store']);
            Route::get('{id}', [CatalogueController::class, 'show']);
            Route::post('{id}', [CatalogueController::class, 'update']);
            Route::delete('{id}', [CatalogueController::class, 'destroy']);
            Route::patch('{id}', [CatalogueController::class, 'restore']);
        });
        /* END */

        /* SIZE PRODUCT */
        Route::group(['prefix' => 'size'], function () {
            Route::get('', [SizeProductController::class, 'index']);
            Route::post('', [SizeProductController::class, 'store']);
            Route::get('{id}', [SizeProductController::class, 'show']);
            Route::put('{id}', [SizeProductController::class, 'update']);
            Route::delete('{id}', [SizeProductController::class, 'destroy']);
            Route::patch('{id}', [SizeProductController::class, 'restore']);
        });
        /* END */

        /* COLOR PRODUCT */
        Route::group(['prefix' => 'color'], function () {
            Route::get('', [ColorProductController::class, 'index']);
            Route::post('', [ColorProductController::class, 'store']);
            Route::get('{id}', [ColorProductController::class, 'show']);
            Route::put('{id}', [ColorProductController::class, 'update']);
            Route::delete('{id}', [ColorProductController::class, 'destroy']);
            Route::patch('{id}', [ColorProductController::class, 'restore']);
        });
        /* END */

        /* MATERIAL */
        Route::group(['prefix' => 'material'], function () {
            Route::get('', [MaterialController::class, 'index']);
            Route::post('', [MaterialController::class, 'store']);
            Route::get('{id}', [MaterialController::class, 'show']);
            Route::put('{id}', [MaterialController::class, 'update']);
            Route::delete('{id}', [MaterialController::class, 'destroy']);
            Route::patch('{id}', [MaterialController::class, 'restore']);
        });
        /* END */

        /* PRODUCT */
        Route::group(['prefix' => 'product'], function () {
            Route::get('', [ProductController::class, 'index']);
            Route::post('', [ProductController::class, 'store']);
            Route::get('{id}', [ProductController::class, 'show']);
            Route::get('show/{id}', [ProductController::class, 'showProduct']);
            Route::get('collection/{id}', [ProductController::class, 'getSizeCollection']);
            Route::put('collection/{id}', [ProductController::class, 'updateCollection']);
            Route::put('{id}', [ProductController::class, 'update']);
            Route::delete('{id}', [ProductController::class, 'destroy']);
            Route::patch('{id}', [ProductController::class, 'restore']);
        });
        /* END */
    });
});

Route::prefix('customer')->group(function () {
    Route::post('login', [App\Http\Controllers\Customer\AuthController::class, 'login']);
    Route::post('logout', [App\Http\Controllers\Customer\AuthController::class, 'logout']);
    Route::group(['middleware' => ['auth:api', 'role:customer', 'role:superadmin']], function () {
    });
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\API\v1\Customer\CustomerAddressController;
use App\Http\Controllers\API\v1\Customer\CustomerWalletController;
use App\Http\Controllers\API\v1\Customer\ShopController as CustomerShopController;
use App\Http\Controllers\API\v1\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\API\v1\Customer\CategoryController as CustomerCategoryController;
use App\Http\Controllers\API\v1\Customer\SearchController as CustomerSearchController;
use App\Http\Controllers\API\v1\Customer\CartController;
use App\Http\Controllers\API\v1\Seller\AuthController as SellerAuthController;
use App\Http\Controllers\API\v1\Seller\ProfileController as SellerProfileController;
use App\Http\Controllers\API\v1\Seller\ShopController as SellerShopController;
use App\Http\Controllers\API\v1\Seller\CategoryController as SellerCategoryController;
use App\Http\Controllers\API\v1\Seller\ProductController as SellerProductController;
use App\Http\Controllers\API\v1\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\API\v1\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\API\v1\Admin\SellerController as AdminSellerController;
use App\Http\Controllers\API\v1\Admin\ShopController as AdminShopController;
use App\Http\Controllers\API\v1\Admin\CategoryController as AdminCategoryController;

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
    return $request->user();
});

Route::group(['prefix' => 'v1/customer'], function(){
    Route::post('/register', [CustomerAuthController::class,'register']);
    Route::post('/login',[CustomerAuthController::class,'login']);

    // Guest Area
    Route::get('shops/',[CustomerShopController::class,'index']);
    Route::get('shops/{id}/', [CustomerShopController::class, 'show']);

    Route::get('shops/{id}/products',[CustomerShopController::class,'products']);

    Route::resource('products', CustomerProductController::class)->only(['index','show']);
    // similar, reviews

    Route::get('categories',[CustomerCategoryController::class,'index']);
    Route::get('search', [CustomerSearchController::class,'index']);

    Route::group(['middleware' => ['auth:customer-api']], function(){
        Route::resource('customer_addresses', CustomerAddressController::class);
        Route::patch('customer_addresses/{id}/selected/',[CustomerAddressController::class,'selected']);
        Route::resource('wallets',CustomerWalletController::class);

        Route::resource('carts',CartController::class);
    });
});

Route::group(['prefix' => 'v1/seller'], function(){
    Route::post('/register', [SellerAuthController::class,'register']);
    Route::post('/login',[SellerAuthController::class,'login']);
    Route::group(['middleware' => ['auth:seller-api']], function(){
        Route::get('/profile', [SellerProfileController::class,'show']);
        Route::post('/shops',[SellerShopController::class,'store']);
        Route::get('/shops', [SellerShopController::class,'show']);

        Route::resource('categories', SellerCategoryController::class)->only(['index']);

        Route::resource('products',SellerProductController::class);
        Route::patch('products/{id}/remove_availability', [SellerProductController::class,'remove_availability']);
        // product images
        // product options
        // product reviews

    });
});

Route::group(['prefix' => 'v1/admin'], function(){
    Route::post('/login',[AdminAuthController::class,'login']);
    Route::group(['middleware' => ['auth:admin-api']], function(){
        Route::resource('customers', AdminCustomerController::class);
        Route::get('sellers/all_owners',[AdminSellerController::class,'all_owners']);
        Route::resource('sellers', AdminSellerController::class);
        Route::resource('shops', AdminShopController::class);
        Route::get('shops/{id}/approve',[AdminShopController::class, 'approve']);

        Route::resource('categories', AdminCategoryController::class);
        // remove image route
    });
});

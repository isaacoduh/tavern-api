<?php

use App\Http\Controllers\API\v1\Customer\OutletController as CustomerOutletController;
use App\Http\Controllers\API\v1\LocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\API\v1\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\API\v1\Customer\CustomerAddressController;
use App\Http\Controllers\API\v1\Customer\CustomerWalletController;
use App\Http\Controllers\API\v1\Customer\ShopController as CustomerShopController;
use App\Http\Controllers\API\v1\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\API\v1\Customer\CategoryController as CustomerCategoryController;
use App\Http\Controllers\API\v1\Customer\SearchController as CustomerSearchController;
use App\Http\Controllers\API\v1\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\API\v1\Customer\CartController;
use App\Http\Controllers\API\v1\Customer\OutletReviewController as CustomerOutletReviewController;
use App\Http\Controllers\API\v1\Seller\AuthController as SellerAuthController;
use App\Http\Controllers\API\v1\Seller\ProfileController as SellerProfileController;
use App\Http\Controllers\API\v1\Seller\ShopController as SellerShopController;
use App\Http\Controllers\API\v1\Seller\CategoryController as SellerCategoryController;
use App\Http\Controllers\API\v1\Seller\ProductController as SellerProductController;
use App\Http\Controllers\API\v1\Seller\OutletController as SellerOutletController;
use App\Http\Controllers\API\v1\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\API\v1\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\API\v1\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\API\v1\Admin\SellerController as AdminSellerController;
use App\Http\Controllers\API\v1\Admin\ShopController as AdminShopController;
use App\Http\Controllers\API\v1\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\PaymentController;

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

Route::get("v1/utils/get-countries-list", [LocationController::class, 'getAllCountries']);
Route::get("v1/utils/get-states-by-country/{country_id}", [LocationController::class, 'getStatesByCountryId']);
Route::get("v1/utils/get-cities-by-state/{state_id}", [LocationController::class, 'getCitiesByState']);

Route::post('s/webhook', [PaymentController::class,'handleStripeWebhook']);

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

    Route::get('outlets/', [CustomerOutletController::class,'index']);
    Route::get('outlets/{id}/', [CustomerOutletController::class, 'show']);
    Route::get('outlets/{id}/products', [CustomerOutletController::class, 'products']);
    Route::group(['middleware' => ['auth:customer-api']], function () {
        Route::get('profile/verify_email/', [CustomerProfileController::class, 'verify_email']);
        Route::post('profile/send_verification_otp', [CustomerProfileController::class, 'send_mobile_verification']);
        Route::post('profile/verify_mobile_number/', [CustomerProfileController::class, 'verify_mobile_number']);
        Route::post('profile/delete_account', [CustomerProfileController::class, 'delete_account']);
        Route::post('profile/send_verification_email/', [CustomerProfileController::class, 'send_verification_email']);
        Route::post('check_mobile_number',[CustomerAuthController::class, 'check_mobile_number']);
        Route::post('logout', [CustomerAuthController::class, 'logout']);


        Route::get('profile/', [CustomerProfileController::class, 'show']);
        Route::patch('profile/', [CustomerProfileController::class, 'update']);
        Route::patch('profile/remove_avatar/', [CustomerProfileController::class, 'remove_avatar']);

        
        Route::resource('customer_addresses', CustomerAddressController::class);
        Route::patch('customer_addresses/{id}/selected/',[CustomerAddressController::class,'selected']);
        Route::resource('wallets',CustomerWalletController::class);
        Route::post('wallets/topup', [CustomerWalletController::class, 'topupWallet']);

        Route::resource('carts',CartController::class);

        Route::get('outlets/{id}/carts', [CustomerOutletController::class, 'carts']);
        Route::get('outlets/{id}/reviews', [CustomerOutletController::class, 'reviews']);

        Route::resource('orders', CustomerOrderController::class);

        // Pay for Order
        Route::post('/orders/{id}/pay', [CustomerOrderController::class, 'pay']);
        Route::post('/orders/{id}/pay/wallet', [CustomerOrderController::class, 'payWithWallet']);
        Route::put('/orders/{id}/cancel', [CustomerOrderController::class, 'cancel']);

        Route::post('outlets/{id}/reviews', [CustomerOutletReviewController::class, 'store']);
        Route::get('outlets/{id}/reviews/me', [CustomerOutletReviewController::class, 'me']);
    });
});

Route::group(['prefix' => 'v1/seller'], function(){
    Route::post('/register', [SellerAuthController::class,'register']);
    Route::post('/login',[SellerAuthController::class,'login']);
    Route::group(['middleware' => ['auth:seller-api']], function(){
        Route::get('/profile', [SellerProfileController::class,'show']);
        Route::post('/shops',[SellerShopController::class,'store']);
        Route::get('/shops', [SellerShopController::class,'show']);

        Route::post('/outlet', [SellerOutletController::class,'create']);

        Route::resource('categories', SellerCategoryController::class)->only(['index']);

        Route::resource('products',SellerProductController::class);
        Route::patch('products/{id}/remove_availability', [SellerProductController::class,'remove_availability']);
        // product images
        // product options
        // product reviews

        Route::resource('orders', SellerOrderController::class)->only(['index','show']);
        Route::patch('orders/{id}/cancel', [SellerOrderController::class, 'cancel']);
        Route::patch('orders/{id}/reject', [SellerOrderController::class, 'reject']);
        Route::patch('orders/{id}/accept', [SellerOrderController::class, 'accept']);
        Route::patch('orders/{id}/deliver', [SellerOrderController::class, 'deliver']);

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

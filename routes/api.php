<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\API\v1\Customer\CustomerAddressController;
use App\Http\Controllers\API\v1\Customer\CustomerWalletController;
use App\Http\Controllers\API\v1\Seller\AuthController as SellerAuthController;
use App\Http\Controllers\API\v1\Admin\AuthController as AdminAuthController;

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

    Route::group(['middleware' => ['auth:customer-api']], function(){
        Route::resource('customer_addresses', CustomerAddressController::class);
        Route::patch('customer_addresses/{id}/selected/',[CustomerAddressController::class,'selected']);
        Route::resource('wallets',CustomerWalletController::class);
    });
});

Route::group(['prefix' => 'v1/seller'], function(){
    Route::post('/register', [SellerAuthController::class,'register']);
    Route::post('/login',[SellerAuthController::class,'login']);
});

Route::group(['prefix' => 'v1/admin'], function(){
    Route::post('/login',[AdminAuthController::class,'login']);
});

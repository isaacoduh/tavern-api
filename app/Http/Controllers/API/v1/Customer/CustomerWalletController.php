<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerWallet;
use Illuminate\Http\Request;

class CustomerWalletController extends Controller
{
    public function index(Request $request)
    {
        $wallet = CustomerWallet::where('customer_id',$request->user()->id)->first();
        return response()->json(['success' => true, 'wallet' => $wallet]);
    }
}

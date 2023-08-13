<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;

class CustomerAddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = CustomerAddress::where('customer_id', $request->user()->id)->get();
        return response()->json(['addresses' => $addresses]);
    }
}

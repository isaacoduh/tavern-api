<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Outlet;

class OutletController extends Controller
{
    // get all outlets on the platform
    public function index(Request $request) 
    {
        $query = Outlet::active();
        if($request->has('city')){
            $query->where('city_id', $request->query('city'));
        }

        if($request->has('delivery_available')) {
            $query->where('delivery_available', $request->query('delivery_available'));
        }
        $outlets = $query->get();
        return response()->json(['success' => true, 'data' => $outlets]);
    }
}

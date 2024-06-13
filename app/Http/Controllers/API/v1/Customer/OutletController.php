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

    public function show(Request $request, $id)
    {
        $outlet = Outlet::active()
            ->where('id', $id)
            ->with(['city:id,name'])
            ->select('id', 'outlet_name', 'city_id', 'location', 'delivery_estimate_min', 'delivery_estimate_max')
            ->first();

        if (!$outlet) {
            return response()->json(['success' => false, 'message' => 'Outlet not found'], 404);
        }

        $outletData = [
            'id' => $outlet->id,
            'outlet_name' => $outlet->outlet_name,
            'city' => $outlet->city ? $outlet->city->name : null,
            'location' => $outlet->location,
            'delivery_estimate_min' => $outlet->delivery_estimate_min,
            'delivery_estimate_max' => $outlet->delivery_estimate_max,
        ];

        return response()->json(['success' => true, 'data' => $outletData]);
    }
}

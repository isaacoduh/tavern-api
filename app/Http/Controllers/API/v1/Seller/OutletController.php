<?php

namespace App\Http\Controllers\API\v1\Seller;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Auth;
use DB;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function create(Request $request)
    {
        $validated_data = $this->validate($request, 
        [   
            'outlet_name' => ['required'],
            'location' => ['required'],
            'phone_number' => ['required'],
            'contact_person' => ['required']
        ]);

        $outlet = new Outlet($validated_data);
        $outlet->seller_id = Auth::id();
        $outlet->active = false;

        DB::beginTransaction();
        $outlet->save();
        DB::commit();

        return response()->json(['success' => true, 'message' => 'Outlet Created Successfully', 'outlet' => $outlet]);
    }
}

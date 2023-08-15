<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $sellers = Seller::all();
        return response()->json(['success' => true, 'sellers' => $sellers]);
    }

    public function show(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);
        return response()->json(['success' => true, 'seller' => $seller]);
    }

    public function all_owners(Request $request)
    {
        $owners = Seller::withAll()->where('is_owner', true)->where('shop_id','!=', null)->get();
        return response()->json(['success' => true, 'owners' => $owners]);
    }

    // TODO: Create an Endpoint to create a seller from ADMIN
    // TODO: Create an Endpoint to update a seller from ADMIN
    // TODO: Create an Endpoint to delete a seller and or delete avatar
}

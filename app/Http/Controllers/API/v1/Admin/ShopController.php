<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $shops = Shop::all();
        return response()->json(['success'=>true, 'shops' => $shops]);
    }

    public function show(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);
        return response()->json(['success' => true, 'shop' => $shop]);
    }

    public function approve(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);
        if($shop->approved){
            return response()->json(['success' => false, 'message' => 'This shop is already approved!']);
        }

        $shop->approved = true;
        $shop->archived = false;
        $shop->save();

        return response()->json(['success' => true, 'message' => 'Shop is approved!']);
    }
}

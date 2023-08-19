<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request) 
    {
        $shops = Shop::all();
        return response()->json(['success' => true, 'shops' => $shops]);
    }

    public function show(Request $request, $id)
    {
        $shop = Shop::with(['products','products.category'])
            ->findOrFail($id);
        return response()->json(['success' => true, 'shop' => $shop]);
    }

    public function products(Request $request, $id)
    {
        $products = Product::withAll()->with('category')->where('shop_id', $id)->get();
        return response()->json(['success' => true, 'products' => $products]);
    }
}

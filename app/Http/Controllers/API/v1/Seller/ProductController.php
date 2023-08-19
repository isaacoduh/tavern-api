<?php

namespace App\Http\Controllers\API\v1\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $shop_id = $request->shop_id;
        $products = Product::with(['shop','category'])->where('shop_id','=',$shop_id)->get();

        return response()->json(['success' => true, 'products' => $products]);
    }

    public function store(Request $request)
    {
        // TODO: shop plan check
        $validated_data = $this->validate($request, [
            'name' => ['required'],
            'shop_id' => ['required'],
            'description' => [],
            'category_id' => ['required', 'exists:categories,id'],
            'active' => ['boolean'],
            'available_from' => ['date_format:H:i',],
            'available_to' => ['required_with:available_from', 'date_format:H:i','after:available_from'],
        ],);
        $shop = Shop::findOrFail($validated_data['shop_id']);
        $category = Category::findOrFail($validated_data['category_id']);

        $product = DB::transaction(function () use($shop, $category, $validated_data, $request){
            $product = new Product($validated_data);
            $product->save();
            // save images
            $product->loadAll();
            return $product;
        });

        return response()->json(['success' => true, 'product' => $product]);
    }

    public function show(Request $request, $id)
    {
        $shop_id = $request->shop_id;
        $product = Product::withAll()->where('shop_id','=',$shop_id)->findOrFail($id);

        return response()->json(['succes' => true, 'product' => $product]);
    }

    public function update(Request $request, $id)
    {
        $shop_id = $request->shop_id;
        $product = Product::where('shop_id','=',$shop_id)->findOrFail($id);
        $validated_data = $this->validate($request, [
            'id' => ['required', 'exists:products,id'],
            'name' => ['required'],
            'shop_id' => ['required'],
            'description' => [],
            'category_id' => ['required', 'exists:categories,id'],
            'active' => ['boolean'],
            'available_from' => ['date_format:H:i',],
            'available_to' => ['required_with:available_from', 'date_format:H:i','after:available_from'],
        ],);
        $product->update($validated_data);
        // save images
        $product->save();
        $product->loadAll();
        return response()->json(['success' => true, 'product' => $product]);
    }

    public function remove_availability(Request $request, $id)
    {
        $shop_id = $request->shop_id;
        $product = Product::where('shop_id', '=', $shop_id)->findOrFail($id);
        $product->available_from = null;
        $product->available_to = null;
        $product->save();
        $product->loadAll();

        return response()->json(['success' => true, 'product' => $product]);
    }

    // public function reviews
}

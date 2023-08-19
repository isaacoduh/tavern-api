<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->query('name');
        $productQuery = Product::withAll()->get();

        return response()->json(['success'=> true, 'products' => $productQuery]);
    }

    public function show(Request $request, $id)
    {
        $product = Product::withAll()->findOrFail($id);
        return response()->json(['success' => true, 'product' => $product]);
    }
}

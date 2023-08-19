<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $carts = Cart::withAll()->where('customer_id', '=', $request->user()->id)->get();
        return response()->json(['success' => true, 'carts' => $carts]);
    }

    public function store(Request $request)
    {
        $validated_data = $this->validate($request, [
            'quantity' => ['numeric', 'min:1'],
            // 'customer_id' => ['required'],
            'product_id' => ['required']
        ]);
        $product = Product::findOrFail($validated_data['product_id']);
        $validated_data['shop_id'] = $product->shop_id;
        $validated_data['customer_id'] = $request->user()->id;

        $cart = Cart::where('product_id',$product->id)->where('customer_id', $request->user()->id)->first();
        if(!isset($cart)){
            $cart = new Cart($validated_data);
            DB::transaction(function () use ($cart){
                $cart->save();
                // add ons
            });
        }
        $cart->loadAll();
        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::withAll()->where('customer_id','=',$request->user()->id)->findOrFail($id);
        $validated_data = $this->validate($request, [
            'quantity' => ['required','numeric','min:0']
        ]);
        $quantity = $validated_data['quantity'];
        // check for stock
        $cart->quantity = $quantity;
        $cart->save();
        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function destroy(Request $request, $id)
    {
        Cart::where('customer_id', $request->user()->id)->findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Cart Deleted!'],204);
    }
}

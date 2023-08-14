<?php

namespace App\Http\Controllers\API\v1\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function store(Request $request)
    {
        $shop_data = $request->get('shop');
        $seller_data = $request->user()->id;;

        $validated_data = $this->validate($request,
            [
                'name' => ['required'],
                'email' => ['required', 'unique:shops,email'],
                'mobile_number' => ['required','unique:shops,mobile_number'],
                'self_delivery' => ['required'],

                'description' => [],
                'address' => ['required'],
                'city' => ['required'],
                'state' => ['required'],
                'country' => ['required'],
                'postcode' => ['required'],
                'tax_type' => ['required','in:percent,amount'],
                'tax' => ['required', 'numeric', 'min:0', function($attribute, $value, $fail){
                    if((request()->has('shop') && request()->get('shop')['tax_type'] == 'percent') || (request()->get('tax_type') == 'percent')){
                        if($value > 100){
                            $fail('Percentage tax can not be more than 100');
                        }
                    }
                },],
                'min_order_amount' => ['nullable','numeric','min:0'],
                'owner_id' => [],
                'open' => ['boolean'],
                'open_for_delivery' => ['boolean'],
            ]
        );

        $shop = new Shop($validated_data);
        // $shop->save();
        $shop->approved = false;
        $shop->active = false;

        DB::transaction(function () use($shop, $seller_data){
            $shop->save();
            if($seller_data){
                $seller = Seller::where('id', $seller_data)->first();
                $seller->save();
                $shop->attachOwner($seller->id);
            }
        });

        return response()->json(['success' => true,'shop' => $shop]);
    }

    public function show(Request $request)
    {
        $shop_id = $request->user()->shop_id;
        $shops = Shop::withAll()->findOrFail($shop_id);
        return response()->json(['success' => true, 'shops' => $shops]);
    }
}

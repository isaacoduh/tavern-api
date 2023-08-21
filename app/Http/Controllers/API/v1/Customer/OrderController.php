<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order_validated_data = $this->validate($request, [
            'customer_id' => ['required'],
            'shop_id' => ['required', 'exists:shops,id'],
            'order_type' => ['in:delivery,self_pickup'],
            'customer_address_id' => ['required_if:order_type,"delivery"','exists:customer_addresses,id'],
            'order_amount' => ['required','numeric','min:0'],
            'tax' => ['required','numeric','min:0'],
            'total' => ['required','numeric','min:0'],
            'delivery_charge' => ['required_if:order_type,"delivery"', 'numeric','min:0'],
            'notes' => [],
        ]);

        $customer_id = $order_validated_data['customer_id'];
        $order = new Order();
        // $orderpaymentid
        $shop_id = $order_validated_data['shop_id'];
        $shop = Shop::findOrFail($shop_id);
        $order_type = $order_validated_data['order_type'];

        $carts = Cart::with('product')->where('shop_id', $order_validated_data['shop_id'])->where('customer_id', $request->user()->id)->get();

        if(count($carts) == 0){
            return response()->json(['error' => true, 'message' => 'Carts are Empty!']);
        }

        $order_amount = 0;
        // coupon discount
        // delivery_charge
        // payment_charge
        foreach ($carts as $cart) {
            // check logic for availability
            $order_amount += $cart->getCartTotal();
        }

        if($order_amount < $shop->min_order_amount){
            return response()->json(['error' => true, 'message' => 'Minimum Order amount is not fulfilled!']);
        }

        if($order_type == 'delivery'){
            if(isset($order_validated_data['customer_address_id'])){
                $customerAddress = CustomerAddress::findOrFail($order_validated_data['customer_address_id']);
            } else {
                return response()->json(['error' => true, 'message' => 'Please provide a delivery address!']);
            }
        } // self pickup 

        // coupon id provided
        $tax = 0;
        // gettaxfromorder
        $packaging_charge = 0;
        $order_commission = 0; // getAdminCommissionForOrder
        $delivery_commission = 0; // business_helper get delivery commision for order;

        // $total_without_payment_charge
        // $total_without_payment_charge = $order_amount + $tax + $packaging_charge + $delivery_charge + $order_commission + $delivery_commission - $coupon_discount;

        $total = $order_amount + $tax + $packaging_charge;
        // payment type = wallet
        // match the total

        $order->notes = $order_validated_data['notes'];
        $order->order_type = $order_validated_data['order_type'];
        $order->order_amount = $order_amount;
        $order->packaging_charge = $packaging_charge;
        $order->tax = $tax;
        $order->order_commision = $order_commission;
        $order->delivery_charge = $delivery_commission;
        $order->payment_charge = 0;
        // $paymentcharge 
        $order->coupon_discount = 0;
        $order->total = $total;
        $order->customer_id = $customer_id;
        $order->shop_id = $shop_id;
        if($order->order_type == 'delivery'){
            $order->customer_address_id = $order_validated_data['customer_address_id'];
        }
        // order coupon id

        DB::transaction(function () use($request, $total, $carts, $order){
            $order->save();
            // save images
            // order payment validated ata

            foreach($carts as $cart){
                $order_item = new OrderItem();
                $order_item->product_id = $cart->product_id;
                $order_item->order_id = $order->id;
                $order_item->price = $cart->product->price;
                $order_item->calculated_price = $cart->product->price;
                $order_item->quantity = $cart->quantity;
                $order_item->save();

                // add ons
                $cart->delete();
            }
        });
        $order->loadAll();
        // send notifications
        return response()->json(['success' => true, 'order' => $order]);
    }

}

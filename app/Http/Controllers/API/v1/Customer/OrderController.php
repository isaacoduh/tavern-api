<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Outlet;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // $order_validated_data = $this->validate($request, [
        //     'customer_id' => ['required'],
        //     'shop_id' => ['required', 'exists:shops,id'],
        //     'order_type' => ['in:delivery,self_pickup'],
        //     'customer_address_id' => ['required_if:order_type,"delivery"','exists:customer_addresses,id'],
        //     'order_amount' => ['required','numeric','min:0'],
        //     'tax' => ['required','numeric','min:0'],
        //     'total' => ['required','numeric','min:0'],
        //     'delivery_charge' => ['required_if:order_type,"delivery"', 'numeric','min:0'],
        //     'notes' => [],
        // ]);
        $order_validated_data = $this->validate($request, [
            'customer_id' => ['required'],
            'outlet_id' => ['required', 'exists:outlets,id'],
            'order_type' => ['in:delivery, self_pickup'],
            'order_amount' => ['required','numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0']
        ]);

        $customer_id = $order_validated_data['customer_id'];
        $order = new Order();
        $outlet_id = $order_validated_data['outlet_id'];

        // search for outlet
        $outlet = Outlet::findOrFail($outlet_id);
        $order_type = $order_validated_data['order_type'];

        $items = $request->items;
        $tax = 0;

        $order_amount = 0;

        foreach ($request->items as $item) {
            $order_amount += $item['unit_price'] * $item['quantity'];
        }

        $total = $order_amount + $tax;
        $order->order_type = $order_validated_data['order_type'];
        $order->order_amount = $order_amount;
        $order->total = $total;

        $order->customer_id = $customer_id;
        $order->outlet_id = $outlet_id;

        DB::transaction(function () use ($request, $total, $items, $order) {
            $order->save();

            foreach ($request->items as $item) {
                $order_item = new OrderItem();
                $order_item->product_id = $item['product_id'];
                $order_item->order_id = $order->id;
                $order_item->price = $item['unit_price'];
                $order_item->calculated_price = $item['unit_price'];
                $order_item->quantity = $item['quantity'];
                $order_item->save();

            }
        });

        $order->loadAll();

        return response()->json(['success' => true, 'order' => $order]);
    }

}

<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CustomerAddress;
use App\Models\CustomerWallet;
use App\Models\CustomerWalletTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Outlet;
use App\Models\Shop;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session;
use Stripe\Stripe;


class OrderController extends Controller
{
    public function index(Request $request){
        $orders = Order::select(['*'])->where('customer_id', $request->user()->id)->orderByDesc('updated_at')->get();
        return response()->json(['success' => true, 'data' => $orders]);
    }

    public function store(Request $request)
    {
        try {
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
            'total' => ['required', 'numeric', 'min:0'],
            'payment_type' => ['in:cash_on_delivery,wallet,card'],
            'customer_address_id' => ['required']
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
        $order->payment_type = $order_validated_data['payment_type'];
        $order->order_amount = $order_amount;
        $order->total = $total;
        $order->total_payment = $total; // this is for payment processor

        $order->customer_id = $customer_id;
        $order->outlet_id = $outlet_id;


            $customer_address_id = $order_validated_data['customer_address_id'];
            $customer_address = CustomerAddress::findOrFail($order_validated_data['customer_address_id']);

            $order->customer_address_id = $customer_address->id;
        

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
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Request $request, $id) {
        $order = Order::with('order_items','outlet')->where('customer_id', $request->user()->id)->findOrFail($id);
        $data = [];
        $orderInfo = [
            'id' => $order->id,
            'otp' => $order->otp,
            'delivery' => $order->order_type,
            'total' => $order->total,
        ];
        $orderItems = [];
        foreach ($order->order_items as $item) {
            $orderItems[] = [
                'id' => $item->id,
                'name' => $item->product->name,
                'price' => $item->price,
                'quantity' => $item->quantity
            ];
        }

        $outletInfo = [
            'outlet_name' => $order->outlet->outlet_name
        ];
        $data['order'] = $orderInfo;
        $data['order']['order_items'] = $orderItems;
        $data['order']['outlet'] = $outletInfo;
        return response()->json(['success' => true, 'order' => $data]);
    }

    public function pay(Request $request, $id) {
        try {
            $order = Order::where([['id', $id], ['customer_id', $request->user()->id]])->findOrFail($id);
        
            if($order->payment_type !== 'card' && $order->payment_status !== 'unpaid'){
                return response()->json(['success' => false, 'message' => 'Error Making Payment']);
            }

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'client_reference_id' => 1,
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'gbp',
                            'unit_amount' => ($order->total_payment) * 100,
                            'product_data' => ['name' => $order->outlet->outlet_name],
                           
                        ],
                        'quantity' => 1
                    ]
                ],
                'payment_intent_data' => [
                    'metadata' => [
                        'customer_id' => $request->user()->id,
                        'order_id' => $order->id,
                        'payment_for' => 'order_placed'
                    ]
                ],
                'mode' => 'payment',
                'success_url' => route('payments.success'),
                'cancel_url' => route('payments.success')
            ]);

            return response()->json(['success' => true, 'checkout_url' => $session->url]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function payWithWallet(Request $request, $id) 
    {
        try {
            $order = Order::where('customer_id', $request->user()->id)->findOrFail($id);

            // check if payment type is wallet
            if($order->payment_type !== 'wallet') {
                return response()->json(['success' => false, 'message' => 'The payment selected for this order is not wallet']);
            }
            $wallet = CustomerWallet::where('customer_id', $request->user()->id)->first();

            if($wallet->balance < $order->total_payment){
                return response()->json(['success' => false, 'message' => 'You do not have enough money in wallet']);
            }

            $amount = $order->total_payment; 

            
            if($wallet->balance >= $order->total_payment){
                $transaction = new CustomerWalletTransaction();
                $transaction->added = false;
                $transaction->amount = $amount;
                $transaction->customer_wallet_id = $wallet->id;
                $transaction->order_id = $order->id;
                $wallet->balance = $wallet->balance - $amount;

                $order->payment_status = 'paid';
                $order->status = 'payment_done';

                DB::transaction(function () use ($order, $wallet, $transaction) {
                    $transaction->save();
                    $wallet->save();
                    $order->save();
                });

            }

            // send payment notification to seller
            return response()->json(['success' => true, 'order' => $order->loadAll()]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

}

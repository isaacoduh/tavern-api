<?php

namespace App\Http\Controllers\API\v1\Seller;

use App\Http\Controllers\Controller;
use App\Models\CustomerWallet;
use App\Models\CustomerWalletTransaction;
use App\Models\Order;
use App\Models\OutletRevenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::withAll()->where('outlet_id', $request->outlet_id)->orderByDesc('updated_at')->get();
        return response()->json(['success' => true, 'orders' => $orders]);
    }

    public function show(Request $request, $id)
    {
        $order = Order::withAll()->where('outlet_id', $request->outlet_id)->where('id', $id)->first();
        return response()->json(['success' => true, 'order' => $order]);
    }

    public function cancel(Request $request, $id)
    {
        $order = Order::withAll()->where('outlet_id', $request->outlet_id)->where('id',$id)->first();
        // check the current status of the order
        if($order->status === "order_placed" || $order->status === "payment_done" || $order->status === "rejected"){
            $order->status = "cancelled_by_outlet";
            $wallet = CustomerWallet::where('customer_id', $order->customer_id)->first();
            $amount = $order->total_payment;
            $order->status = true;

            DB::transaction(function () use ($order, $wallet, $amount) {
                if($order->payment_status === 'paid'){
                    $transaction = new CustomerWalletTransaction();
                    $transaction->added = true;
                    $transaction->amount = $amount;
                    $transaction->customer_wallet_id = $wallet->id;
                    $transaction->order_id = $order->id;
                    $wallet->balance = $wallet->balance + $amount;
                    $transaction->save();
                }

                $order->save();
                $wallet->save();
            });

            // send notificaiton to customer
            return response()->json(['success' => true, 'message' => 'Order Cancelled', 'order' => $order]);
        } else {
            return response()->json(['success' => true, 'message' => 'Order cannot be cancelled']);
        }
    }

    public function reject(Request $request, $id)
    {
        $order = Order::withAll()->where('outlet_id', $request->outlet_id)->where('id',$id)->first();
        if($order->status === 'order_placed' || $order->status === 'resubmit' || $order->status === 'payment_done'){
            $order->status = 'rejected';
            $order->status_description = $request->description;
            $order->save();

            return response()->json(['success' => true, 'message' => 'Order Rejected', 'order' => $order]);
        } else {
            return response()->json(['success' => true, 'message' => 'Order cannot be rejected']);
        }

    }

    public function accept(Request $request, $id)
    {
        $order = Order::withAll()->where('outlet_id', $request->outlet_id)->where('id',$id)->first();
        if($order->status !== 'cancelled_by_outlet' || $order->status !== 'cancelled_by_customer'){
            $order->status = 'accepted';
            $order->save();

            return response()->json(['success' => true, 'message' => 'Order Accepted', 'order' => $order]);
        } else {
            return response()->json(['success' => true, 'message' => 'Order cannot be accpeted']);
        }
    }

    public function deliver(Request $request, $id)
    {
        $order = Order::withAll()->where('outlet_id', $request->outlet_id)->where('id',$id)->first();
        if($order->status === 'accepted' || $order->status === 'ready_for_delivery'){
            DB::transaction(function () use ($order) {
                $order->status = 'delivered';
                if($order->complete) {
                    return true;
                }

                $outlet_revenue_amount = $order->order_amount + $order->tax + $order->packaging_charge;
                $admin_revenue_amount = $order->total - $outlet_revenue_amount;
                $total = $order->total;

                $order->admin_revenue_amount = $admin_revenue_amount;
                $order->outlet_revenue_amount = $outlet_revenue_amount;
                

                // new admin revenue
                $outlet_revenue = new OutletRevenue();

                $outlet_revenue->revenue = $outlet_revenue_amount;
                $outlet_revenue->order_amount = $order->order_amount;
                $outlet_revenue->tax = $order->tax;
                $outlet_revenue->packaging_charge = $order->packaging_charge ? $order->packaging_charge : 0;

                $outlet_revenue->order_id = $order->id;
                $outlet_revenue->outlet_id = $order->outlet_id;

                $order->complete = true;

                $order->save();
                $outlet_revenue->save();
                
            });

            return response()->json(['success' => false, 'message' => 'Order Delivered', 'order' => $order]);
        } else {
            return response()->json(['success' => false, 'message' => 'Order cannot be delivered']);
        
        }
    }

}

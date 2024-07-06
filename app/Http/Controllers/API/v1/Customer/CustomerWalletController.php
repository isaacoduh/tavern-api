<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerWallet;
use Exception;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CustomerWalletController extends Controller
{
    public function index(Request $request)
    {
        $wallet = CustomerWallet::where('customer_id',$request->user()->id)->first();
        return response()->json(['success' => true, 'wallet' => $wallet]);
    }

    public function topupWallet(Request $request){
        try {
            $wallet = CustomerWallet::where('customer_id', $request->user()->id)->first();

            $wallet_validated_data = $this->validate($request, [
                'amount' => ['required', 'numeric']
            ]);

            echo $wallet->id;

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'client_reference_id' => 1,
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'gbp',
                            'unit_amount' => ($wallet_validated_data['amount']) * 100,
                            'product_data' => ['name' => 'Wallet Top up (Tavern)']
                        ],
                        'quantity' => 1,
                    ]
                ],
                'payment_intent_data' => [
                    'metadata' => [
                        'customer_id' => $request->user()->id,
                        'wallet_id' => $wallet->id,
                        'payment_for' => 'wallet_topup'
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
}

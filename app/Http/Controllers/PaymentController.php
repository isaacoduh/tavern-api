<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Order;

class PaymentController extends Controller
{
    public function handleStripeWebhook(){
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        

        $data = [
            'payload' => [$payload],
            'header' => [$_SERVER],
            'stripe-signature' => [$sig_header]
        ];

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            $data['error'] = 'Invalid payload';
            Log::channel('daily')->error('Error: Received Webhooks for Stripe', $data);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            $data['error'] = 'Invalid signature';
            Log::channel('daily')->error('Error: Received Webhooks for Stripe', $data);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if($event->type === 'charge.succeeded'){
            $paymentInfo = [];

            $object = $event->data->object;
            $order_id = $object->metadata->order_id;
            $order = Order::find($order_id);

            $paymentInfo['metadata'] = $object->metadata;

            Log::channel('daily')->info('...payment info', $paymentInfo);

            if ($order) {
                    $order->payment_status = 'paid';
                $order->status = 'payment_done';
                    $order->save();
                    Log::channel('daily')->info('Order updated to paid', ['order_id' => $order_id]);
                } else {
                    // Log::channel('daily')->warning('Order not found for payment intent', ['payment_intent' => $paymentIntent]);
                }
        }

        Log::channel('daily')->info('Received Webhooks for Stripe', $data);
    }
}

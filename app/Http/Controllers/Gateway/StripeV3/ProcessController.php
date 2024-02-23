<?php

namespace App\Http\Controllers\Gateway\StripeV3;

use App\Constants\Status;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class ProcessController extends Controller
{

    public static function process($deposit)
    {

        if ($deposit->regular == 0) {
        return self::normalPaymnet($deposit);
        } else {
            return self::processSubscription($deposit);
        }

    }


    private static function normalPaymnet($deposit){
        $stripeAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        $alias = $deposit->gateway->alias;
        $general = gs();
        
        \Stripe\Stripe::setApiKey($stripeAcc->secret_key);

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $deposit->method_currency,
                        'unit_amount' => round($deposit->final_amo, 2) * 100,
                        'product_data' => [
                            'name' => $general->site_name,
                            'description' => 'Deposit with Stripe',
                            'images' => [asset('assets/images/logoIcon/logo.png')],
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route(gatewayRedirectUrl(true)),
                'cancel_url' => route(gatewayRedirectUrl()),
            ]);
        } catch (\Exception $e) {
            $send['error'] = true;
            $send['message'] = $e->getMessage();
            return json_encode($send);
        }

        $send['view'] = 'user.payment.'.$alias;
        $send['session'] = $session;
        $send['StripeJSAcc'] = $stripeAcc;
        $deposit->btc_wallet = json_decode(json_encode($session))->id;;
        
        Log::info("process in ProcessController for stripe");
        $deposit->save();

        return json_encode($send);
    }

    public static function processSubscription($deposit)
    {

        $stripeAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        $alias = $deposit->gateway->alias;
        $general = gs();

        \Stripe\Stripe::setApiKey($stripeAcc->secret_key);

        try {
            // Create a new price in Stripe based on the user's input
            $price = \Stripe\Price::create([
                'currency' => $deposit->method_currency,
                'unit_amount' => round($deposit->final_amo, 2) * 100,
                'recurring' => ['interval' => 'month'],
                'product_data' => [
                    'name' => 'Waqfinity Donation subscription',
                ],
            ]);

            // Use the newly created price ID to create the subscription session
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $price->id,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route(gatewayRedirectUrl(true)),
                'cancel_url' => route(gatewayRedirectUrl()),
            ]);
        } catch (\Exception $e) {
            $send['error'] = true;
            $send['message'] = $e->getMessage();
            return json_encode($send);
        }

        $send['view'] = 'user.payment.'.$alias;
        $send['session'] = $session;
        $send['StripeJSAcc'] = $stripeAcc;
        $deposit->btc_wallet = json_decode(json_encode($session))->id;

        Log::info("process in ProcessController for stripe");
        $deposit->save();

        return json_encode($send);



    }


    public function ipn(Request $request)
    {
        Log::info("ipn for strip with dr moahmed ");
        $StripeAcc = GatewayCurrency::where('gateway_alias','StripeV3')->orderBy('id','desc')->first();
        $gateway_parameter = json_decode($StripeAcc->gateway_parameter);


        \Stripe\Stripe::setApiKey($gateway_parameter->secret_key);

        // You can find your endpoint's secret in your webhook settings
        $endpoint_secret = $gateway_parameter->end_point; // main
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];


        $event = null;
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
             
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            
            http_response_code(400);
            exit();
        }

        // Handle the checkout.session.completed event
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $deposit = Deposit::where('btc_wallet',  $session->id)->orderBy('id', 'DESC')->first();

            if($deposit->status==Status::PAYMENT_INITIATE){
                
                PaymentController::userDataUpdate($deposit);
            }
        }
        http_response_code(200);
    }
    
    
    
    
    
//    public  function ipn(Request $request)
//    {
//        Log::info("ipn for strip with dr moahmed ");
//      
////        $track = session()->get('Track');
////        $donation = session()->get('DONATION');
////        
////        $deposit = Deposit::where('trx', $track)->where("user_id", auth()->user()->id)->where("donation_id", $donation->id)->where('status',Status::PAYMENT_INITIATE)->first();
////      
////        $StripeAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
////        $alias = $deposit->gateway->alias;
////        $general = gs();
////        \Stripe\Stripe::setApiKey("$StripeAcc->secret_key");
////        $session = \Stripe\Checkout\Session::retrieve($deposit->btc_wallet);
////        
////        if ($session->payment_status === 'paid') {
////          
////            $deposit = Deposit::where('btc_wallet',  $session->id)->orderBy('id', 'DESC')->first();
////
////            if($deposit->status==Status::PAYMENT_INITIATE){
////                Log::info("in ipn for strip3 PAYMENT_INITIATE");
////                PaymentController::userDataUpdate($deposit);
////            }
////        }
////        return to_route(gatewayRedirectUrl(true));
//        //         old code\
//        $StripeAcc = GatewayCurrency::where('gateway_alias','StripeV3')->orderBy('id','desc')->first();
//        $gateway_parameter = json_decode($StripeAcc->gateway_parameter);
//
//
//        \Stripe\Stripe::setApiKey($gateway_parameter->secret_key);    
//        // You can find your endpoint's secret in your webhook settings
//        $endpoint_secret = $gateway_parameter->end_point; // main
//        $payload = @file_get_contents('php://input');
//        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
//
//
//        $event = null;
//        try {
//            $event = \Stripe\Webhook::constructEvent(
//                $payload, $sig_header, $endpoint_secret
//            );
//        } catch(\UnexpectedValueException $e) {
//            // Invalid payload
//            http_response_code(400);
//            exit();
//        } catch(\Stripe\Exception\SignatureVerificationException $e) {
//            // Invalid signature
//            http_response_code(400);
//            exit();
//        }
//       
//        // Handle the checkout.session.completed event
//        if ($event->type == 'checkout.session.completed') {
//            $session = $event->data->object;
//            $deposit = Deposit::where('btc_wallet',  $session->id)->orderBy('id', 'DESC')->first();
//
//            if($deposit->status==Status::PAYMENT_INITIATE){
//                Log::info("in ipn for strip3 PAYMENT_INITIATE");
//                PaymentController::userDataUpdate($deposit);
//            }
//        }
//        http_response_code(200);
//        
//    }
}



<?php

namespace App\Http\Controllers\Gateway;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Donation;
use App\Models\GatewayCurrency;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function deposit()
    {
      
        if(!session()->has('DONATION')){
            $notify[]=['success',"Please donate a campaign"];
            return to_route('campaign.index')->withNotify($notify);
        }
        $donation        = session()->get('DONATION');
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('method_code')->get();
        $pageTitle = 'Deposit Methods';
        return view($this->activeTemplate . 'user.payment.deposit', compact('gatewayCurrency', 'pageTitle','donation'));
    }

    public function depositInsert(Request $request)
    {
        if(!session()->has('DONATION')){
            $notify[]=['success',"Please donate a campaign"];
            return to_route('campaign.index')->withNotify($notify);
        }
        $request->validate([
            'amount'   => 'required|numeric|gt:0',
            'gateway'  => 'required',
            'currency' => 'required',
        ]);

        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->gateway)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }
        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = ['error', 'Please follow donation limit'];
            return back()->withNotify($notify);
        }

        $donation  = session()->get('DONATION');
        $charge    = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable   = $request->amount + $charge;
        $final_amo = $payable * $gate->rate;
        
       
                
                
        $data                  = new Deposit();
        $data->user_id         = $donation->user_id;           //campaign_creator
        $data->donation_id     = $donation->id;
        $data->regular         = $donation->regular;
        $data->method_code     = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount          = $request->amount;
        $data->charge          = $charge;
        $data->rate            = $gate->rate;
        $data->final_amo       = $final_amo;
        $data->btc_amo         = 0;
        $data->btc_wallet      = "";
        $data->trx             = getTrx();
        $data->save();
        
//        if (str_contains($gate->name, 'Stripe')) { 
//            $donation->status = 1;
//            $donation->save();
//        }
        session()->put('Track', $data->trx);

        return to_route('deposit.confirm');
    }

    public function depositConfirm()
    {
        $track = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return to_route('deposit.manual.confirm');
        }
        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';
        Log::info("depositConfirm for strip ");
        $data = $new::process($deposit);

        $data = json_decode($data);

        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return to_route(gatewayRedirectUrl())->withNotify($notify);
        }
        if (isset($data->redirect)) {
             Log::info("Payment Confirm for strip data url".$data->redirect_url);
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if (@$data->session) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = 'Payment Confirm';
        Log::info("Payment Confirm for strip");
        
        return view($this->activeTemplate . $data->view, compact('data', 'pageTitle', 'deposit'));
    }


    public static function userDataUpdate($deposit, $isManual = null)
    {
        Log::info("userDataUpdate for strip3 ");
         
        if ($deposit->status == Status::PAYMENT_INITIATE || $deposit->status == Status::PAYMENT_PENDING) {
            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->save();

            $donation         = $deposit->donation;
            $donation->status = Status::DONATION_PAID;
            $donation->save();

            $campaign=$donation->campaign;

            $user           = $campaign->user;
            $user->balance += $deposit->amount;
            $user->save();

            if (auth()->check()) {

                if (!$isManual) {
                    $adminNotification = new AdminNotification();
                    $adminNotification->user_id = $user->id;
                    $adminNotification->title = 'Payment successful via ' . $deposit->gatewayCurrency()->name;
                    $adminNotification->click_url = urlPath('admin.deposit.successful');
                    $adminNotification->save();
                }

                notify(auth()->user(), $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                    'method_name'     => $deposit->gatewayCurrency()->name,
                    'method_currency' => $deposit->method_currency,
                    'method_amount'   => showAmount($deposit->final_amo),
                    'amount'          => showAmount($deposit->amount),
                    'charge'          => showAmount($deposit->charge),
                    'rate'            => showAmount($deposit->rate),
                    'trx'             => $deposit->trx,
                    'post_balance'    => showAmount($user->balance)
                ]);
            }

            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->amount       = $deposit->amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge       = $deposit->charge;
            $transaction->trx_type     = '+';
            $transaction->details      = "New donation on ". strLimit($campaign->title,15)."campaign";
            $transaction->trx          = $deposit->trx;
            $transaction->remark       = 'donation_received';
            $transaction->save();

            if (session()->has('DONATION')) {
                session()->forget('DONATION');
            }
        }
    }

    public function manualDepositConfirm()
    {
     
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }

        if ($data->method_code > 999) {
            $pageTitle = 'Payment Confirm';
            $method    = $data->gatewayCurrency();
            $gateway   = $method->method;
            return view($this->activeTemplate . 'user.payment.manual', compact('data', 'pageTitle', 'method', 'gateway'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    { 
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        $gatewayCurrency = $data->gatewayCurrency();
        $gateway = $gatewayCurrency->method;
        $formData = $gateway->form->form_data;

        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);


        $data->detail = $userData;
        $data->status = Status::PAYMENT_PENDING;
        $data->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $data->user->id;
        $adminNotification->title = 'Payment request from ' . $data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        notify($data->user, 'DEPOSIT_REQUEST', [
            'method_name'     => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount'   => showAmount($data->final_amo),
            'amount'          => showAmount($data->amount),
            'charge'          => showAmount($data->charge),
            'rate'            => showAmount($data->rate),
            'trx'             => $data->trx
        ]);

        $notify[] = ['success', 'Your donation request has been taken'];
        return to_route('campaign.index')->withNotify($notify);
    }
}

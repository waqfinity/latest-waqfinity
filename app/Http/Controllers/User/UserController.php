<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Form;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
        $user      = auth()->user();

        $campaign['allCampaign']    = Campaign::where('user_id', $user->id)->count();
        $campaign['pending']        = Campaign::where('status', Status::PENDING)->where('user_id', $user->id)->count();

        $campaign['received_donation']  =Donation::paid()->whereHas('campaign',function($q) use($user){
                $q->where('user_id',$user->id);
        })->sum('donation');

        $campaign['my_donation']      = Donation::where('user_id', $user->id)->paid()->sum('donation');

        $campaign['completed']      = Campaign::active()->completed()->where('user_id', $user->id)->count();
        $campaign['rejectLog']      = Campaign::where('user_id', $user->id)->where('status', status::REJECTED)->count();
        $campaign['withdraw']       = Withdrawal::where('user_id', $user->id)->where('status', '!=', Status::PAYMENT_INITIATE)->sum('amount');
        $campaign['currentBalance'] = User::where('id', $user->id)->sum('balance');
        $campaign['expired']        = Campaign::active()->where('user_id', $user->id)->where('deadline', '<', now())->count();

        //Graph report
        $donation = Donation::where('user_id', $user->id)->paid()
            ->selectRaw('sum(donation) as totalAmount')
            ->selectRaw('monthname(created_at) month')
            ->groupBy('month')->get();

        $donations['perDay']       = collect([]);
        $donations['perDayAmount'] = collect([]);

        $donation->map(function ($a) use ($donations) {
            $donations['perDay']->push($a->month);
            $donations['perDayAmount']->push($a->totalAmount + 0);
        });


        $withdraw = Withdrawal::where('user_id', $user->id)->where('created_at', '>=', Carbon::now()->subDays(30))->where('status', 1)
            ->selectRaw('sum(amount)   as totalAmount')
            ->selectRaw('monthname(created_at) month')
            ->groupBy('month')->get();

        $withdraws['perDay']       = collect([]);
        $withdraws['perDayAmount'] = collect([]);

        $withdraw->map(function ($withdraw) use ($withdraws) {
            $withdraws['perDay']->push($withdraw->month);
            $withdraws['perDayAmount']->push($withdraw->totalAmount + 0);
        });

        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'campaign', 'donations', 'withdraws', 'user'));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.deposit_history', compact('pageTitle', 'deposits'));
    }


    // public function submitOnboard()
    // {
    //     return view('templates.basic.user.user-onboard');
    // }

    public function show2faForm()
    {
        $general   = gs();
        $ga        = new GoogleAuthenticator();
        $user      = auth()->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Setting';
        return view($this->activeTemplate . 'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts  = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions()
    {
        $pageTitle    = 'Transactions Log';
        $remarks      = Transaction::distinct('remark')->where('remark', '!=', 'anonymous_donation')->orderBy('remark')->get('remark');
        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == 2) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == 1) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form = Form::where('act', 'kyc')->first();
        return view($this->activeTemplate . 'user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user = auth()->user();
        $pageTitle = 'KYC Data';
        return view($this->activeTemplate . 'user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
       
        $form = Form::where('act', 'kyc')->first();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        
        $userData = $formProcessor->processFormData($request, $formData);
        $user = auth()->user();
        $user->kyc_data = $userData;
        $user->kv = 2;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function attachmentDownload($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general = gs();
        $title = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function userData()
    {
        $user = auth()->user();
        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }
        $pageTitle = 'User Data';
        return view($this->activeTemplate . 'user.user_data', compact('pageTitle', 'user'));
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = [
            'country' => @$user->address->country,
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'city' => $request->city,
        ];
        $user->profile_complete = 1;
        $user->save();

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);
    }
    
    public function storeOnboardData(Request $request)
    {
        dd($request->all());
    
    }



}

<?php

namespace App\Http\Controllers\User\Auth;

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

class OnBoardController extends Controller
{
   

    public function showCreatePageForm()
    {
        
       
        $pageTitle = 'Account Recovery';
        return view('views.templates.basic.partials.onboard-create-page', compact('pageTitle'));
        
//        $pageTitle = 'Deposit History';
//        $deposits = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
//        return view($this->activeTemplate . 'user.deposit_history', compact('pageTitle', 'deposits'));
    }

    


}

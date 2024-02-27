<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use App\Models\GatewayCurrency;
use Carbon\Carbon;
class Deposit extends Model
{
    use Searchable;

    protected $casts = [
        'detail' => 'object'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'method_code', 'code');
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function(){
            $html = '';
            if($this->status == Status::PAYMENT_PENDING){
                $html = '<span class="badge badge--warning">'.trans('Pending').'</span>';
            }
            elseif($this->status == Status::PAYMENT_SUCCESS && $this->method_code >= 1000){
                $html = '<span><span class="badge badge--success">'.trans('Approved').'</span><br>'.diffForHumans($this->updated_at).'</span>';
            }
            elseif($this->status == Status::PAYMENT_SUCCESS && $this->method_code < 1000){
                $html = '<span class="badge badge--success">'.trans('Succeed').'</span>';
            }
            elseif($this->status == Status::PAYMENT_REJECT){
                $html = '<span><span class="badge badge--danger">'.trans('Rejected').'</span><br>'.diffForHumans($this->updated_at).'</span>';
            }else{
                $html = '<span class="badge badge--dark">'.trans('Initiated').'</span>';
            }
            return $html;
        });
    }

    // scope
    public function scopeGatewayCurrency()
    {
        return GatewayCurrency::where('method_code', $this->method_code)->where('currency', $this->method_currency)->first();
    }

    public function scopeBaseCurrency()
    {
        return @$this->gateway->crypto == Status::ENABLE ? 'USD' : $this->method_currency;
    }

    public function scopePending($query)
    {
        return $query->where('method_code','>=',1000)->where('status', Status::PAYMENT_PENDING);
    }

    public function scopeRejected($query)
    {
        return $query->where('method_code','>=',1000)->where('status', Status::PAYMENT_REJECT);
    }

    public function scopeApproved($query)
    {
        return $query->where('method_code','>=',1000)->where('status', Status::PAYMENT_SUCCESS);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', Status::PAYMENT_SUCCESS);
    }

    public function scopeInitiated($query)
    {
        return $query->where('status', Status::PAYMENT_INITIATE);
    }

    public static function getSubscriptionDetailsBySessionId($sessionId)
        {
            // Retrieve the Stripe account information
            $stripeAcc = GatewayCurrency::where('gateway_alias', 'StripeV3')->orderBy('id', 'desc')->first();
            $gatewayParameters = json_decode($stripeAcc->gateway_parameter);

            // Set the Stripe API key
            \Stripe\Stripe::setApiKey($gatewayParameters->secret_key);

            try {
                // Retrieve session details from Stripe
                $session = \Stripe\Checkout\Session::retrieve($sessionId);
                // Extract subscription ID from the session
                $subscriptionId = $session->subscription;

                // Retrieve subscription details from Stripe
              $subscription = \Stripe\Subscription::retrieve($subscriptionId);

                // Now you can access properties of the subscription object directly
                $subscriptionId = $subscription->id;
                $customer = $subscription->customer;
                $billing = $subscription->current_period_end;
                $currentPeriodEnd = Carbon::createFromTimestamp($billing);
                $formattedDate = $currentPeriodEnd->format('F d, Y');
                // And so on...

                // Return the subscription object itself
                return $formattedDate;
            } catch (\Exception $e) {
                // Handle any errors
                // You might want to handle errors more gracefully
                // You can throw an exception or log the error
                return null;
            }
        }


}

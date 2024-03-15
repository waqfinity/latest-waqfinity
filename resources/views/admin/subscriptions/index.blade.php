@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Billing Cycle')</th>
                                    <th>@lang('Progress')</th>
                                    <th>@lang('Next Renewal Date')</th>
                                    <th>@lang('From')</th>
                                    <th>@lang('Details')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription->donation->fullname }}</td>
                                        <td class="tr-{{ \App\Models\Deposit::getSubscriptionDetailsBySessionId($subscription->btc_wallet, 'status') }}">{{ \App\Models\Deposit::getSubscriptionDetailsBySessionId($subscription->btc_wallet, 'status') }}</td>
                                        <td>£{{ number_format($subscription->donation->donation, 2) }} / per month</td>
                                        <td> {{ \App\Models\Deposit::getSubscriptionDetailsBySessionId($subscription->btc_wallet, 'time_period') }} / @if(\App\Models\Deposit::getSubscriptionDetailsBySessionId($subscription->btc_wallet, 'status') == 'active')
                                            Ongoing
                                            @else
                                            {{ \App\Models\Deposit::getSubscriptionDetailsBySessionId($subscription->btc_wallet, 'status') }}
                                        @endif


                                       </td>
                                       <td>{{ \App\Models\Deposit::getSubscriptionDetailsBySessionId($subscription->btc_wallet, 'date') }}</td>
                                       <td>{{ $subscription->donation->campaign->title}}</td>
                                       <td><a target="_blank" href="https://dashboard.stripe.com/subscriptions/{{ \App\Models\Deposit::getSubscriptionDetailsBySessionId($subscription->btc_wallet, 'id') }}">View Details</a></td>
                                     </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
 
            </div><!-- card end -->
        </div>
    </div>
    @endsection
      
    @push('style')
    <style>
        .tr-active{
            color: green !important;
        }
    </style>
    @endpush




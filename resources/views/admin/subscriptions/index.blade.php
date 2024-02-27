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
                                    <th>@lang('Email')</th>
                                    <th>@lang('Total Amount')</th>
                                    <th>@lang('Next Renewal Date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription->donation->fullname }}</td>
                                        <td>{{ $subscription->donation->email }}</td>
                                        <td>£{{ number_format($subscription->donation->donation, 2) }}</td>
                                       <td>{{ \App\Models\Deposit::getSubscriptionDetailsBySessionId($subscription->btc_wallet) }}</td>


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
      




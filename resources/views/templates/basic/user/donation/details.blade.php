@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="container">
        <div class="card card-style custom--shadow">
            <div class="card-header">
                <h4 class="card-title text-light">
                    <i class="fa fa-list font-style"></i>
                    @lang('Details of Donor'): {{ $donor->fullname }}
                </h4>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-4">
                        <h5>@lang('Campaign') : </h5>
                    </div>
                    <div class="col-md-8">
                        <p>
                            <a href="{{ route('user.campaign.fundrise.view', ['slug' => $donor->campaign->slug, 'id' => $donor->campaign->id]) }}"
                                title="@lang('Details')">{{ __($donor->campaign->title) }}
                            </a>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <h3>@lang('Full Name') : </h3>
                    </div>
                    <div class="col-md-8">
                        <p>{{ __($donor->fullname) }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <h3>@lang('Email') : </h3>
                    </div>
                    <div class="col-md-8">
                        <p>{{ $donor->email }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <h3>@lang('Country') : </h3>
                    </div>
                    <div class="col-md-8">
                        <p>{{ $donor->country }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <h3>@lang('Mobile') : </h3>
                    </div>
                    <div class="col-md-8">
                        <p>{{ $donor->mobile }}</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <h3>@lang('Amount') : </h3>
                    </div>
                    <div class="col-md-8">
                        <p>{{ $general->cur_sym }}{{ showAmount($donor->donation, 2) }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <h3>@lang('Payment Method') : </h3>
                    </div>
                    <div class="col-md-8">
                        <p>{{ @$donor->deposit->gateway->alias }}</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <h3>@lang('Payment Date') : </h3>
                    </div>
                    <div class="col-md-8">
                        <p>{{ showDateTime(@$donor->deposit->created_at )}} ( {{ diffForHumans(@$donor->deposit->created_at )}})</p>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection

@push('style')
    <style>
        .card-header {
            background: hsl(var(--base));
        }
        .card-style {
            margin: 50px auto;
            width: 60%;
        }
    </style>
@endpush

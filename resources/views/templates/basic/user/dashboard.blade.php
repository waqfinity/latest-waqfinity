@php
    $kycInstruction = getContent('kyc_instruction.content', true);
    $campaigns =  \App\Models\Campaign::where('user_id', auth()->id())->where("status", 1)->get();
    $user=auth()->user();
@endphp
@extends($activeTemplate . 'layouts.master')
@section('content')
    <!-- dashboard section start -->
    <section class="pt-120 pb-120">
        <div class="container">
            <div class="row gy-4">
            @foreach($campaigns as $campaign)
                @php
                    $donation = \App\Models\Donation::where("campaign_id", $campaign->id)->where("status", 1)->where("donation", '>' , 0)->first();
                @endphp

                @if(empty($donation))
                    <div class="alert alert-info" role="alert">
                        <h4 class="alert-heading">@lang('Campaign Approved: Add Seed Money')</h4>
                        <hr>
                        <p class="mb-0"> Congratulations! Your campaign "{{ $campaign->title }}" is live. Please add seed money within 7 days (by {{ \Carbon\Carbon::parse($campaign->deadline)->format('M d, Y') }}) to confirm its legitimacy.<br>Failure to do so will lead to archiving.</p>
                        <br>
                        <h3 class="mb-3">@lang('Amount')</h3>
                         <form class="vent-details-form" method="POST"
                                action="{{ route('campaign.donation.process', [$campaign->slug, $campaign->id]) }}">
                                @csrf
                                <div class="form-row align-items-center">
                                    <div class="col-lg-12 form-group donate-amount">
                                        <div class="input-group mr-sm-2">
                                            <div class="input-group-text">{{ $general->cur_sym }}</div>
                                            <input type="number" id="donateAmount" class="form-control" value="0"
                                                name="amount" required>
                                        </div>
                                    </div>
                                    <div class="col-12 form-group donated-amount">
                                        <div class="form--radio form-check-inline">
                                            <input class="form-check-input donation-radio-check" type="radio"
                                                name="customRadioInline1" value="100" id="customRadioInline1">
                                            <label class="form-check-label" for="customRadioInline1">
                                                {{ $general->cur_sym }}@lang('100')
                                            </label>
                                        </div>
                                        <div class="form--radio form-check-inline">
                                            <input class="form-check-input donation-radio-check" type="radio"
                                                name="customRadioInline1" value="200" id="customRadioInline2">
                                            <label class="form-check-label" for="customRadioInline2">
                                                {{ $general->cur_sym }}@lang('200')
                                            </label>
                                        </div>
                                        <div class="form--radio form-check-inline">
                                            <input class="form-check-input donation-radio-check" type="radio"
                                                name="customRadioInline1" value="300" id="customRadioInline3">
                                            <label class="form-check-label" for="customRadioInline3">
                                                {{ $general->cur_sym }}@lang('300')
                                            </label>
                                        </div>
                                        <div class="form--radio form-check-inline">
                                            <input class="form-check-input donation-radio-check custom-donation"
                                                type="radio" name="customRadioInline1" id="flexRadioDefault4">
                                            <label class="form-check-label" for="flexRadioDefault4">
                                                @lang('Custom')
                                            </label>
                                        </div>
                                    </div>
                               </div>
                                @php
                                    $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
                                    $countryArray = (array)$countryData;
                                    $countryCode = $user->country_code;
                                @endphp

                                @foreach($countryArray as $code => $countryDetails)
                                    @if($code === $countryCode)
                                        @php
                                            $country = $countryDetails->country;
                                        @endphp
                                        @break
                                    @endif
                                @endforeach

                                <div>
                                  <input type="text" name="email" value="{{ $user->email }}" hidden>
                                  <input type="text" name="mobile" value="{{ $user->mobile }}" hidden>        
                                   <input type="hidden" name="campaign_id" value="{{ $campaign->id}}">      
                                  <input type="text" name="country" value="{{ $country }}" hidden>        
                                 <input type="text" name="name" value="{{ $user->firstname . ' ' . $user->lastname }}" hidden>
                                </div>
                        <div class="d-flex align-items-center gap-3">
                             <button type="submit" class="cmn-btn w-45">@lang('Donate')</button>  
                        </div>
                    </div>
                </form>
                @endif
            @endforeach
                <div id="showOnboardAlert" style="padding: 0;">
                    
                </div>
                @if(isset($showAlert) && $showAlert === 'kyc')
                    <div class="alert alert-info" role="alert">
                        <h4 class="alert-heading">@lang('Launch onboard')</h4>
                        <hr>
                        <p class="mb-0"> 
                            <a
                                class="text--base" href="{{ route('user.onboarding') }}">@lang('Click Here to launch')</a></p>
                    </div>
                @endif
                @if ($user->kv == 0)
                    <div class="alert alert-info" role="alert">
                        <h4 class="alert-heading">@lang('KYC Verification required')</h4>
                        <hr>
                        <p class="mb-0">{{ __($kycInstruction->data_values->verification_instruction) }} <a
                                class="text--base" href="{{ route('user.kyc.form') }}">@lang('Click Here to Verify')</a></p>
                    </div>
                @elseif($user->kv == 2)
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">@lang('KYC Verification pending')</h4>
                        <hr>
                        <p class="mb-0">{{ __($kycInstruction->data_values->pending_instruction) }} <a class="text--base"
                                href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></p>
                    </div>
                @endif

                @if ($campaign['expired'] > 0)
                    <div class="offset-lg-8 col-lg-4 col-md-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <a class="text-danger" href="{{ route('user.campaign.fundrise.expired') }}" class="text-primary">
                                @lang('Campaign Expired') (<strong>{{ $campaign['expired'] }}</strong>)
                            </a>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                <div class="col-lg-3 col-sm-6 mb-30">
                    <div class="d-widget bg-one">
                        <div class="d-widget__icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="d-widget__content">
                            <h2 class="d-widget__number text-white">{{ $campaign['allCampaign'] }}</h2>
                            <span class="text-white">@lang('Total Campaign')</span>
                        </div>
                        <a href="{{ route('user.campaign.fundrise.all') }}" class="view-btn">@lang('View all')</a>

                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-30">
                    <div class="d-widget bg-four">
                        <div class="d-widget__icon">
                            <i class="fas fa-spinner"></i>
                        </div>
                        <div class="d-widget__content">
                            <h2 class="d-widget__number text-white">{{ $campaign['pending'] }}</h2>
                            <span class="text-white">@lang('Pending Campaign')</span>
                        </div>
                        <a href="{{ route('user.campaign.fundrise.pending') }}" class="view-btn">@lang('View all')</a>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 mb-30">
                    <div class="d-widget bg-five">
                        <div class="d-widget__icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div class="d-widget__content">
                            <h2 class="d-widget__number text-white">{{ $campaign['completed'] }}</h2>
                            <span class="text-white">@lang('Campaign Completed')</span>
                        </div>
                        <a href="{{ route('user.campaign.fundrise.complete') }}" class="view-btn">@lang('View all')</a>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 mb-30">
                    <div class="d-widget bg-danger">
                        <div class="d-widget__icon">
                            <i class="fa fa-times"></i>
                        </div>
                        <div class="d-widget__content">
                            <h2 class="d-widget__number text-white">{{ $campaign['rejectLog'] }}</h2>
                            <span class="text-white">@lang('Campaign Rejected')</span>
                        </div>
                        <a href="{{ route('user.campaign.fundrise.rejected') }}" class="view-btn">@lang('View all')</a>
                    </div><!-- d-widget end -->
                </div>

                <div class="col-lg-3 col-sm-6 mb-30">
                    <div class="d-widget bg-two">
                        <div class="d-widget__icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="d-widget__content">
                            <h2 class="d-widget__number text-white">
                                {{ $general->cur_sym }}{{ showAmount($campaign['received_donation']) }}</h2>
                            <span class="text-white">@lang('Total Received Donation')</span>
                        </div>
                        <a href="{{ route('user.campaign.donation.received') }}" class="view-btn">@lang('View all')</a>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 mb-30">
                    <div class="d-widget bg-three">
                        <div class="d-widget__icon">
                            <i class="fas fa-donate"></i>
                        </div>
                        <div class="d-widget__content">
                            <h2 class="d-widget__number text-white">{{ $general->cur_sym }}{{ showAmount($campaign['my_donation']) }}</h2>
                            <span class="text-white">@lang('My Donation')</span>
                        </div>
                        <a href="{{ route('user.campaign.donation.my') }}" class="view-btn">@lang('View all')</a>
                    </div>
                </div>




                <div class="col-lg-3 col-sm-6 mb-30">
                    <div class="d-widget bg-seven">
                        <div class="d-widget__icon">
                            <i class="fa fa-money"></i>
                        </div>
                        <div class="d-widget__content">
                            <h2 class="d-widget__number text-white">
                                {{ $general->cur_sym }}{{ showAmount($campaign['withdraw']) }}</h2>
                            <span class="text-white">@lang('Total Withdraw')</span>
                        </div>
                        <a href="{{ route('user.withdraw.history') }}" class="view-btn">@lang('View all')</a>
                    </div><!-- d-widget end -->
                </div>

                <div class="col-lg-3 col-sm-6 mb-30">
                    <div class="d-widget bg-primary">
                        <div class="d-widget__icon">
                            <i class="las la-dollar-sign"></i>
                        </div>
                        <div class="d-widget__content">
                            <h2 class="d-widget__number text-white">
                                {{ $general->cur_sym }}{{ showAmount($campaign['currentBalance']) }}</h2>
                            <span class="text-white">@lang('Current Balance')</span>
                        </div>

                    </div><!-- d-widget end -->
                </div>

                <div class="col-md-6 mb-30">
                    <div class="card custom--shadow">
                        <div class="card-body">
                            <h5 class="card-title">@lang('Monthly Donation Report')</h5>
                            <div id="apex-line"> </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-30">
                    <div class="card custom--shadow">
                        <div class="card-body">
                            <h5 class="card-title">@lang('Monthly Withdraw Report')</h5>
                            <div id="apex-line-withdraw"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- dashboard section end -->
@endsection

@push('script')
    <script src="{{ asset($activeTemplateTrue . 'js/apexchart.js') }}" charset="utf-8"></script>
    <script>
        'use strict';

        //apex-line chart:  Donation
        var options = {
            series: [{
                data: @json($donations['perDayAmount'])
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '15%',
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: @json($donations['perDay'])
            }
        };

        //apex-line chart: Withdraw
        var withdraw = {
            series: [{
                data: @json($withdraws['perDayAmount'])
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '10%',
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: @json($withdraws['perDay'])
            }
        };

        var chart = new ApexCharts(document.querySelector("#apex-line"), options);
        var chart2 = new ApexCharts(document.querySelector("#apex-line-withdraw"), withdraw);

        chart.render();
        chart2.render();
    </script>
    <script>
      var onboardingProcess = localStorage.getItem('onboardingProcess');
      console.log(onboardingProcess);

          // Function to remove the alert and clear localStorage
    function removeAlertAndClearStorage() {
        var showOnboard = document.getElementById('showOnboardAlert');
        if (showOnboard) {
            showOnboard.remove();
        }

        localStorage.removeItem('onboardingProcess');
    }

    // Check if the value is 'kyc' and display the alert
    if (onboardingProcess === 'kyc' || onboardingProcess === 'impactConfigaration') {
        var alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success';
        alertDiv.setAttribute('role', 'alert');

        alertDiv.innerHTML = `
            <h4 class="alert-heading">@lang('Launch onboard form')</h4>
            <hr>
            <p style="color:#000">You have started the creation of a new Waqf page during your onboarding process. Please click 'Continue' to processed with page creation or 'Dismiss' to cancel the process.</p><p class="mb-0 d-flex"><a
                class="cmn-btn me-3" href="{{ route('user.onboard') }}">@lang('Continue')</a><a
                class="btn btn-danger" id="cancelOnboard" style="height:50px;display:flex;align-items:center;justify-content:center;background:#808080c7;border-color:transparent">@lang('Dismiss')</a></p>
        `;

        // Append the alert to the document body or the desired location
        var showOnboardAlert = document.getElementById('showOnboardAlert');
        showOnboardAlert.appendChild(alertDiv);

        // Add an event listener to the button with id "cancelOnboard"
        var cancelOnboardButton = document.getElementById('cancelOnboard');
        cancelOnboardButton.addEventListener('click', function() {
            removeAlertAndClearStorage();
        });
    }
    //donation-checkbox
        $(".donation-radio-check").on('click', function(e) {
            $(".donation-radio-check").attr('checked', false);
            $(this).prop('checked', true);
            $("[name=amount]").val($(this).val())
        });

        $("#donateAmount").on('click', function(e) {
            $(".donation-radio-check").prop('checked', false);
            $(".custom-donation").prop('checked', true);
            $(this).val("");
        });

        $(".custom-donation").on('click', function(e) {
            $("[name=amount]").focus();
            $("[name=amount]").val();
        });
    </script>
@endpush
@push('style') 
<style>
    .alert-success{
        background-color: #d1e7dd75 !important;
        border-color: #d1e7dd75 !important;
    }
</style>
@endpush

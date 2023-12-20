@php
    $content   = getContent('top_donors.content', true);
    $topDonors = App\Models\Donation::paid()->limit(12)->groupBy('email')->selectRaw("*,sum(donation) as totalDonations")->orderBy('totalDonations', 'DESC')->get();
@endphp
<section class="pt-120 pb-120 border-top-1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="section-header">
                    <h2 class="section-title">{{ __(@$content->data_values->heading) }}</h2>
                    <p>{{ __(@$content->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center gy-4">
            @foreach ($topDonors as $data)
                <div class="col-xl-3 col-sm-6 col-xsm-6">
                    <div class="top-donor-item">
                        <h3 class="top-donor-item__position"> <span class="text">{{ ordinal($loop->iteration) }}</span> </h3>
                        <div class="top-donor-item__content">
                            <h5 class="top-donor-item__name"> {{ $data->fullname }} </h5>
                            <h5 class="top-donor-item__amount mb-0 text--base">@lang('Donation'): {{ $general->cur_sym }}{{ showAmount($data->totalDonations)  }} </h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

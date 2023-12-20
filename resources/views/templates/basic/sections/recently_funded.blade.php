@php
    $campaignContent = getContent('recently_funded.content', true);
    $campaigns = App\Models\Donation::paid()->groupBy('campaign_id')
        ->with('campaign.donation', 'campaign.category')
        ->whereHas('campaign', function ($campaign) {
            $campaign->running();
        })
        ->selectRaw('*,sum(donation) as donate')->latest('id')->take(3)->get()
        ->map(function ($campaign) {
            return $campaign->campaign;
        });
@endphp
<section class="campaign-section pt-120 pb-150 position-relative base--bg">
    <div class="section-img">
        <img src="{{ getImage($activeTemplateTrue . 'images/texture-3.jpg') }}" alt="@lang('image')">
    </div>
<!--    <div class="top-shape">
        <img src="{{ getImage($activeTemplateTrue . 'images/top_texture.png') }}" alt="@lang('image')">
    </div>-->
<!--    <div class="bottom-shape">
        <img src="{{ asset($activeTemplateTrue . 'images/top-shape.png') }}" alt="@lang('image')">
    </div>-->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="section-header my-5">
                    <h2 class="section-title text-white">{{ __($campaignContent->data_values->heading) }}</h2>
                    <p class="text-white">{{ __($campaignContent->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row gy-4 gy-4 justify-content-center">

            @include($activeTemplate . 'partials.campaign')

            @if ($campaigns->count() > 6)
                <div class="col-md-12 my-5 text-center">
                    <a href="{{ route('campaign.index') }}" class="cmn-btn">@lang('Show All Campaigns')</a>
                </div>
            @endif
        </div>
    </div>
</section>

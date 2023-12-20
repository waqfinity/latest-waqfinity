@php
    $campaignContent = getContent('campaign.content', true);
    $campaigns = App\Models\Campaign::running()->with('donation', 'category')->orderBy('id', 'DESC')->take(3)->get();
@endphp

<section class="campaign-section pt-120 pb-120 position-relative base--bg">
    <div class="section-img">
        <img src="{{ getImage($activeTemplateTrue . 'images/texture-3.jpg') }}">
    </div>
    <div class="bottom-shape">
        <!--<img src="{{ asset($activeTemplateTrue . 'images/top-shape.png') }}" >-->
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-8">
                <div class="section-header text-center">
                    <h2 class="section-title text-white">{{ __($campaignContent->data_values->title) }}</h2>
                    <p class="text-white">{{ __($campaignContent->data_values->description) }}</p>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row gy-4 gy-4 justify-content-center">
            @include($activeTemplate . 'partials.campaign')
            <div class="col-md-12 my-5 text-center">
                <a href="{{ route('campaign.index') }}" class="cmn-btn">@lang('SHOW ALL CAMPAIGNS')</a>
            </div>
        </div>
    </div>
</section>

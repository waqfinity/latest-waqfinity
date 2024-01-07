@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-120 pb-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="event-details-wrapper">
                        <div class="event-details-thumb">
                            <img src="{{ getImage(getFilePath('campaign') . '/' . $campaign->image, getFileSize('campaign')) }}"
                                alt="@lang('image')">
                        </div>
                    </div>
                    <div class="event-details-area mt-50">
                        <ul class="nav nav-tabs nav-tabs--style" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="description-tab" data-bs-toggle="tab" href="#description"
                                    role="tab" aria-controls="description" aria-selected="true">@lang('Description')</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="gallery-tab" data-bs-toggle="tab" href="#gallery" role="tab"
                                    aria-controls="gallery" aria-selected="false">@lang('Proof Image')</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="video-tab" data-bs-toggle="tab" href="#pdf" role="tab"
                                    aria-controls="pdf" aria-selected="false">@lang('Proof Document')</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="review-tab" data-bs-toggle="tab" href="#review" role="tab"
                                    aria-controls="review" aria-selected="false">@lang('Comment')</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-4" id="myTabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel"
                                aria-labelledby="description-tab">
                                <p class="text-justify"> @php echo $campaign->description @endphp</p>
                            </div><!-- tab-pane end -->
                            <div class="tab-pane fade" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
                                <div class="row gy-4">
                                    @foreach ($campaign->proof_images as $images)
                                        @if (explode('.', $images)[1] != 'pdf')
                                            <div class="col-lg-4 col-sm-6 mb-30">
                                                <div class="gallery-card">
                                                    <a href="{{ asset(getFilePath('proof') . '/' . $images) }}"
                                                        class="view-btn" data-rel="lightcase:myCollection"><i
                                                            class="las la-plus"></i></a>
                                                    <div class="gallery-card__thumb">
                                                        <img src="{{ asset(getFilePath('proof') . '/' . $images) }}"
                                                            alt="@lang('image')">
                                                    </div>
                                                </div><!-- gallery-card end -->
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div><!-- tab-pane end -->
                            <div class="tab-pane fade" id="pdf" role="tabpanel" aria-labelledby="pdf-tab">
                                @foreach ($campaign->proof_images as $pdfFiles)
                                    @if (explode('.', $pdfFiles)[1] == 'pdf')
                                        <iframe class="iframe" src="{{ asset(getFilePath('proof') . '/' . $pdfFiles) }}" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                    @endif
                                @endforeach
                            </div><!-- tab-pane end -->
                            <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                                <ul class="review-list mb-50">
                                    @forelse($campaign->comments->where('status',Status::PUBLISHED) as $comment)
                                        <li class="single-review">
                                            <div class="thumb"><i class="fa fa-user comment-user"></i></div>
                                            <div class="content">
                                                <h6 class="name mb-1">{{ __($comment->fullname) }}</h6>
                                                <span class="date">{{ diffforhumans($comment->created_at) }}</span>
                                                <p class="mt-2 text-justify">{{ __($comment->comment) }}</p>
                                            </div>
                                        </li>
                                    @empty
                                        <p class="text-center border py-3">@lang('No review yet!')</p>
                                    @endforelse
                                </ul>
                            </div><!-- tab-pane end -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-lg-0">
                    <div class="donation-sidebar custom--shadow">
                        <div class="donation-widget">
                            <h3>{{ strLimit($campaign->title, 20) }}</h3>
                            <p style="word-break: break-all;"> @php  echo strLimit(strip_tags($campaign->description), 120); @endphp </p>
                            <hr>
                            <div class="row mt-2 justify-content-between">
                                <div class="col-sm-6 text-center">
                                    <b>{{ $general->cur_sym }}{{ showAmount($campaign->donation->where('status', Status::DONATION_PAID)->sum('donation')) }}</b>
                                    <br> @lang('Donated')
                                </div>
                                <div class="col-sm-6 text-center">
                                    @lang('Goal Amount') <br> 
                                    @if ($campaign->goal > 999999)
                                   <b>Ongoing</b>
                                   @else
                                   <b>{{ $general->cur_sym }}{{ showAmount($campaign->goal) }}</b>
                                   @endif
                               
                                </div>
                            </div>
                            <div class="row mt-50 mb-none-30">
                                <div class="col-6 donate-item text-center mb-30">
                                    <h4 class="amount">{{ $campaign->donation->where('status', Status::DONATION_PAID)->count() }}
                                    </h4>
                                    <p>@lang('Donors')</p>
                                </div>
                                <div class="col-6 donate-item text-center mb-30">
                                    <h4 class="amount">
                                        {{ $general->cur_sym }}{{ showAmount($campaign->donation->where('status', Status::DONATION_PAID)->sum('donation')) }}
                                    </h4>
                                    <p>@lang('Donated')</p>
                                </div>
                            </div>
                        </div><!-- donation-widget end -->

                        <div class="donation-widget">
                            <h3>@lang('Event Share')</h3>
                            <div class="link-copy copy mt-3">
                                    <input type="text" id="urlCopyId"
                                        value="{{ route('campaign.details', ['slug' => $campaign->slug, 'id' => $campaign->id]) }}"
                                        class="form-control">
                                    <button type="button" class="copyText">@lang('Copy')</button>
                            </div>
                            <ul class="social-links mt-4">
                                <li class="facebook"><a target="_blank"
                                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i
                                            class="fab fa-facebook-f"></i></a></li>
                                <li class="twitter"><a target="_blank"
                                        href="https://twitter.com/intent/tweet?text=Post and Share &amp;url={{ urlencode(url()->current()) }}"><i
                                            class="fab fa-twitter"></i></a></li>
                                <li class="linkedin"><a target="_blank"
                                        href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}"><i
                                            class="fab fa-linkedin-in"></i></a></li>
                                <li class="whatsapp"> <a
                                        href="https://api.whatsapp.com/send?text={{ urlencode(url()->current()) }}"><i
                                            class="fab fa-whatsapp"></i></a></li>
                            </ul>
                        </div><!-- donation-widget end -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- event details section end -->
<!--     <div class="container d-flex align-items-center gap-4">
    @php
        $totalCategories = count($campaign->allDonationCategoriesNames) - 1;
        $selectedCategories = $campaign->CampaignDonationCategoriesNames;
        $letWaqfinityDecide = in_array("Let Waqfinity Decide", $selectedCategories);
        $equalPercentage = $letWaqfinityDecide ? 100 / $totalCategories : 0;
    @endphp

    @foreach ($campaign->allDonationCategoriesNames as $category)
        @php
            if ($category == "Let Waqfinity Decide") {
                $percentage = $letWaqfinityDecide ? $equalPercentage : 0;
            } else {
                $isSelected = in_array($category, $selectedCategories);
                $percentage = $isSelected ? (1 / $totalCategories) * 100 : 0;
            }
        @endphp
         @if ($category != "Let Waqfinity Decide")
        <div class="d-flex flex-column align-items-center app-bg-highlight">
            <div class="circle_percent" data-percent="{{ $percentage }}">
                <div class="circle_inner">
                    <div class="round_per"></div>
                </div>
            </div>

            <label>{{ $category }}</label>
        </div>
        @endif
    @endforeach
</div> -->
 @if ($campaign->isCorporate !== 1)
    <div class="container">
           <hr>
        
    </div>
   <div class="container my-3">
       @php
         $campaignContent = getContent('target_impact.content', true);
       @endphp
         <h2 class="text-dark" style="font-size: 1.5rem">   {{ __($campaignContent->data_values->heading) }}</h2>
   <p style="font-size: 1rem">{{ __($campaignContent->data_values->subheading) }}</p>
   </div>
    <div class="container d-flex align-items-center gap-xl-4 gap-3 flex-wrap ">
        @php
            $totalCategories = count($campaign->CampaignDonationCategoriesNames);
            $totalCategories2 = count($campaign->allDonationCategoriesNames);
            $selectedCategories = $campaign->CampaignDonationCategoriesNames;
            $letWaqfinityDecide = in_array("Let Waqfinity Decide", $selectedCategories);
            $equalPercentage = $letWaqfinityDecide ? 100 / ($totalCategories2 - 1) : 100 / $totalCategories;
        @endphp

        @foreach ($campaign->allDonationCategoriesNames as $category)
            @php
                $isSelected = in_array($category, $selectedCategories);
                $percentage = $isSelected ? $equalPercentage : 0;
            @endphp
             @if ($category != "Let Waqfinity Decide")
            <div class="d-flex flex-column align-items-center app-bg-highlight">
                @if ($letWaqfinityDecide)
                 <div class="circle_percent" data-percent="{{ $equalPercentage }}">
                @else
                <div class="circle_percent" data-percent="{{ $percentage }}">
                @endif
                    <div class="circle_inner">
                        <div class="round_per"></div>
                    </div>
                </div>

                <label>{{ $category }}</label>
            </div>
            @endif
        @endforeach
         @endif
    </div>
@endsection


@push('style')
    <style>
.iframe {
    width: 100%;
    height: 800px;
}

.app-bg-highlight{
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    padding: 20px;
    border-radius: 6px;
    width: 220px;
    height: 220px;
    justify-content: center;
}
.app-bg-highlight label{
    text-align: center;
    font-size: 14px;
}
.circle_percent {
    font-size: 200px;
    width: 0.5em;
    height: 0.5em;
    position: relative;
    background: #eee;
    border-radius: 50%;
    overflow: hidden;
    display: inline-block;
    margin: 20px;
}
.circle_inner {
    position: absolute;
    left: 0;
    top: 0;
    width: 0.5em;
    height: 0.5em;
    clip: rect(0 0.5em 0.5em 0.25em);
}
.round_per {
    position: absolute;
    left: 0;
    top: 0;
    width: 0.5em;
    height: 0.5em;
    background: #187681;
    clip: rect(0 0.5em 0.5em 0.25em);
    transform: rotate(180deg);
    transition: 1.05s;
}
.percent_more .circle_inner {
    clip: rect(0 0.5em 1em 0em);
}
.percent_more:after {
    position: absolute;
    left: 0.5em;
    top: 0em;
    right: 0;
    bottom: 0;
    background: #187681;
    content: "";
}
.circle_inbox {
    position: absolute;
    top: 10px;
    left: 10px;
    right: 10px;
    bottom: 10px;
    background: #fff;
    z-index: 3;
    border-radius: 50%;
}
.percent_text {
    position: absolute;
    font-size: 16px;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    z-index: 3;
}

.pb-60{
    padding-bottom: 60px;
}

@media (min-width: 360px) and (max-width: 480px){

    .app-bg-highlight{
        box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        padding: 1rem;
        border-radius: 6px;
        width: calc(100% / 2 - 1rem);
        height: auto;
        justify-content: center;
    }

}

@media (max-width: 360px){

    .app-bg-highlight{
        box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        padding: 1rem;
        border-radius: 6px;
        width: 100% ;
        height: auto;
        justify-content: center;
    }

}

</style>
@endpush

@push('script')
    <script>
        'use strict';
        //copy-url
        $('.copyText').on('click', function() {
            var copyText = document.getElementById("urlCopyId");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            notify('success', 'URL copied successfully');
        })
    </script>
    <script>
        $(".circle_percent").each(function() {
    var $this = $(this),
        $dataV = $this.data("percent"),
        $dataDeg = $dataV * 3.6,
        $round = $this.find(".round_per");
    $round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)"); 
    $this.append('<div class="circle_inbox"><span class="percent_text"></span></div>');
    $this.prop('Counter', 0).animate({Counter: $dataV},
    {
        duration: 2000, 
        easing: 'swing', 
        step: function (now) {
            $this.find(".percent_text").text(Math.ceil(now)+"%");
        }
    });
    if($dataV >= 51){
        $round.css("transform", "rotate(" + 360 + "deg)");
        setTimeout(function(){
            $this.addClass("percent_more");
        },1000);
        setTimeout(function(){
            $round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)");
        },1000);
    } 
});
    </script>
@endpush

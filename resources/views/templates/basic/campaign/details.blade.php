@php
    $donor    = $campaign->donation->where('status', Status::DONATION_PAID);
    $donation = $donor->sum('donation');
    $percent  = percent($donation,$campaign);
    $campaignContent = getContent('target_impact.content', true);
@endphp

@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- event details section start -->
    <section class="pt-120 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="event-details-wrapper">
                        <div class="event-details-thumb">
                            <img src="{{ getImage(getFilePath('campaign') . '/' . $campaign->image, getFileSize('campaign')) }}"
                                alt="@lang('image')">
                        </div>
                    </div><!-- event-details-wrapper end -->
                    <div class="event-details-area mt-50">
                        <ul class="nav nav-tabs nav-tabs--style" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                    data-bs-target="#description" href="#description" role="tab"
                                    aria-controls="description" aria-selected="true">@lang('Description')</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery"
                                    href="#gallery" role="tab" aria-controls="gallery"
                                    aria-selected="false">@lang('Relevent Image')</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="video-tab" data-bs-toggle="tab" data-bs-target="#document"
                                    href="#video" role="tab" aria-controls="document"
                                    aria-selected="false">@lang('Relevent Document')</a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review"
                                    href="#review" role="tab" aria-controls="review"
                                    aria-selected="false">@lang('Comments')</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-4" id="myTabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel"
                                aria-labelledby="description-tab">
                                <p class="text-justify">@php echo $campaign->description @endphp</p>
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
                            <div class="tab-pane fade" id="document" role="tabpanel" aria-labelledby="document-tab">
                                @foreach ($campaign->proof_images as $pdfFiles)
                                    @if (explode('.', $pdfFiles)[1] == 'pdf')
                                        <iframe width="100%" height="800"
                                            src="{{ asset(getFilePath('proof') . '/' . $pdfFiles) }}" frameborder="0"
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
                                                <h6 class="name">{{ __($comment->fullname) }}</h6>
                                                <small class="date">{{ diffforhumans($comment->updated_at) }}</small>

                                                <p class="mt-1 text-justify">{{ __($comment->comment) }}</p>
                                            </div>
                                        </li>
                                    @empty
                                        <p class="text-center border py-3">@lang('No Comment Yet')</p>
                                    @endforelse
                                </ul>
                                <form action="{{ route('campaign.comment') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" name="campaign" value="{{ $campaign->id }}">
                                        <div class="form-group col-lg-6">
                                            <input type="text" name="fullname" placeholder="@lang('Enter Name')"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <input type="email" name="email" placeholder="@lang('Enter Email Address')"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <textarea placeholder="@lang('Enter Your Comment')" class="form-control" name="comment"></textarea>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="cmn-btn w-45">@lang('SUBMIT COMMENT')</button>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- tab-pane end -->

                        </div>
                    </div>
                    <hr>
                <div class="section-header my-4">
                    <h2 class="text-dark" style="font-size: 1.5rem">   {{ __($campaignContent->data_values->heading) }}</h2>
                    <p style="font-size: 1rem">{{ __($campaignContent->data_values->subheading) }}</p>
                </div>
                        <div class="container d-flex align-items-center gap-xl-4 gap-3 flex-wrap px-0 mt-4">
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
                    </div>
                </div>
                <div class="col-lg-4 mt-lg-0 mt-5">
                    <div class="donation-sidebar">
                        <div class="donation-widget">
                            <pg class="title">{{ $campaign->title }}</pg>
                            <div class="skill-bar mt-5">
                                <div class="progressbar" data-perc="{{ $percent > 100 ? '100' : $percent }}%">
                                    <div class="bar"></div>
                                    <span class="label">{{ showAmount($percent > 100 ? '100' : $percent) }}%</span>
                                </div>
                            </div>
                            <div class="row mt-2 justify-content-between">
                                <div class="col-sm-6">
                                    @lang('Donated') <br>
                                    <b>{{ $general->cur_sym }}{{ showAmount($donation) }}
                                    </b>

                                </div>
                                <div class="col-sm-6">
                                    @lang('Goal Amount') <br>
                                     @if ($campaign->goal > 999999)
                                       <b>Ongoing</b>
                                       @else
                                        <b>{{ $general->cur_sym }}{{ showAmount($campaign->goal) }}</b>
                                       @endif
                               
                                </div>
                            </div>
                            <div class="row mt-50 mb-none-30">
                                <div class="col-6 donate-item mb-30">
                                    <h4 class="amount">{{ $donor->count() }}</h4>
                                    <p>@lang('Donors')</p>
                                </div>
                                <div class="col-6 donate-item mb-30">
                                    <h4 class="amount"> {{ $general->cur_sym }}{{ showAmount($donation) }}</h4>
                                    <p>@lang('Donated')</p>
                                </div>
                            </div>
                        </div><!-- donation-widget end -->
                        <div class="donation-widget">
                            <form class="vent-details-form" method="POST"
                                action="{{ route('campaign.donation.process', [$campaign->slug, $campaign->id]) }}">
                                @csrf
                                <h3 class="mb-3">@lang('Donate Amount')</h3>
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
                                
<!--                                 <h3 class="mb-4 mt-30">@lang('Select Donations Category')</h3>
                          
                              
                                @foreach($campaignsDonationCategories as $booking)
                                    <div class="form--radio form-check-inline">
                                            <input class="form-check-input-test donation-radio-check-test custom-donation-test"
                                                type="radio" name="donation_category_id" id="{{$booking->donation_category_id}}"  value="{{$booking->donation_category_id}}">
                                            <label class="form-check-label-test" for="{{$booking->donation_category_id}}">
                                             {{$booking->name}}
                                            </label>
                                            
                                </div>
                                @endforeach-->
                                <h3 class="mb-4 mt-30">@lang('Personal Information')</h3>

                                @if ($general->anonymous_donation)
                                    <div class="form--check mb-4">
                                        <input class="form-check-input" type="checkbox" name="anonymous" id="checkdon"
                                            value="1">
                                        <label class="form-check-label" for="checkdon">
                                            @lang('Make Anonymous Donation')
                                        </label>
                                    </div>
                                @endif

                                
                                @php
                                    $user=auth()->user();
                                @endphp
                                <div class="form-row">
                                    <div class="form-group col-lg-12">
                                        <label>@lang('Full Name')</label>
                                        <input type="text" name="name" value="{{old('name',@$user->fullname)}}" class="form-control checktoggle" required>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label>@lang('Email')</label>
                                        <input type="text" name="email" value="{{ old('email',@$user->email)}}" class="form-control checktoggle" required>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label>@lang('Mobile'): </label>
                                        <input type="number" name="mobile" value="{{ old('mobile',@$user->mobile)}}" class="form-control checktoggle" required>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label>@lang('Country')</label>
                                        <input type="text" name="country" value="{{ old('country',@$user->address->country)}}" class="form-control checktoggle"required>
                                    </div>                                    

                                    <div class="form-group col-lg-12">
                                        <label>@lang('Postcode')</label>
                                        <input type="text" name="postcode" class="form-control checktoggle"required>
                                    </div>
                                    <div class="form-group d-flex gap-2 form--check mb-4" style="flex-wrap:nowrap">
                                  
                                    <input class="form-check-input" type="checkbox" name="accept_marketing" id="checkbox" >
                                    <label for="checkbox">@lang('Approve For Us To Use Details For Marketing.')</label>
                                    </div>
                                    <img src="https://upload.wikimedia.org/wikipedia/en/9/9f/Gift_Aid_UK_Logo.svg" alt="Gift Aid UK Logo" style="max-width: 100px; margin-left: 30px;">
                                    <div class="form-group d-flex gap-2 form--check mb-4" style="flex-wrap:nowrap">
                                  
                                    <input class="form-check-input" type="checkbox" name="giftaid" id="checkboxgiftaid" style="min-width:16px">
                                    <label for="checkboxgiftaid" style="font-size: 10px;">@lang('Yes, I am a UK taxpayer and I would like Mercy Mission UK to reclaim the tax on all qualifying donations I have made, as well as any future donations, until I notify them otherwise.

                                     <br> I understand that if I pay less Income Tax and/or Capital Gains Tax than the amount of Gift Aid claimed on all my donations in that tax year it is my responsibility to pay any difference.')</label>
                                    </div>
                                    <div class="col-lg-12">
                                        <input type="hidden" name="campaign_id" value="{{ $campaign->id}}">
                                        <button type="submit" class="cmn-btn w-100" @if (@auth()->user()->id == $campaign->user_id)  @endif>@lang('DONATE NOW')</button>
                                        <!--<button type="submit" class="cmn-btn w-100" @if (@auth()->user()->id == $campaign->user_id) disabled @endif>@lang('DONATE NOW')</button>-->
                                       
                                    </div>
                                </div>
                                
                            </form>
                        </div>

                        <div class="donation-widget">
                            <h3>@lang('Event Share')</h3>
                            <div class="link-copy copy mt-3">
                                    <input type="text" id="urlCopyId"
                                        value="{{ route('campaign.details', ['slug' => $campaign->slug, 'id' => $campaign->id]) }}"
                                        class="form-control">
                                    <button type="button" class="copyText">@lang('COPY')</button>
                            </div>
                            <ul class="social-links mt-4">
                                <li class="facebook face"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i class="fab fa-facebook-f"></i></a></li>
                                <li class="twitter twi"><a target="_blank"
                                        href="https://twitter.com/intent/tweet?text=Post and Share &amp;url={{ urlencode(url()->current()) }}"><i
                                            class="fab fa-twitter"></i></a></li>
                                <li class="linkedin lin"><a target="_blank"
                                        href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}"><i
                                            class="fab fa-linkedin-in"></i></a></li>
                                <li class="whatsapp what"><a target="_blank"
                                        href="https://wa.me/?text={{ urlencode(url()->current()) }}"><i
                                            class="fab fa-whatsapp"></i></a></li>
                            </ul>
                        </div><!-- donation-widget end -->

                        <div class="donation-widget pb-5">
                            <h3 class="mb-4">@lang('Latest Donation')</h3>
                            <ul class="donor-small-list">

                                @php
                                    $allDonors = $donor;
                               
                                @endphp
                                
                                @forelse($allDonors->take(4) as $donor)
                                    <li class="single">
                                        <div class="thumb feature-card__icon "><i class="fa fa-user"></i></div>
                                        <div class="content">
                                            @if($donor->anonymous == 1)         
                                                <h6>{{ "Anonymous" }}</h6>
                                            @else
                                                <h6>{{ $donor->fullname }}</h6>
                                            @endif
                                            
                                            <p>@lang('Amount') :
                                                {{ $general->cur_sym }}{{ showAmount($donor->donation) }}</p>
                                        </div>
                                    </li>
                                @empty
                                    {{ __($emptyMessage) }}
                                @endforelse

                                @if ($allDonors->count() > 4)
                                    <li class="single">
                                        <button type="button"
                                            class="donarModal cmn-btn w-100">@lang('See All Donors')</button>
                                    </li>
                                @endif
                            </ul>
                        </div><!-- donation-widget end -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- event details section end -->

    <!-- Modal -->
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('All Donors')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="donation-widget pb-5">
                        <ul class="donor-small-list">
                            @foreach ($allDonors as $donor)
                                <li class="single">
                                    <div class="thumb feature-card__icon "><i class="fa fa-user"></i></div>
                                    <div class="content">
                                        @if($donor->anonymous == 1)         
                                                <h6>{{ "Anonymous" }}</h6>
                                            @else
                                                <h6>{{ $donor->fullname }}</h6>
                                            @endif
                                        <p>@lang('Amount') :
                                            {{ $general->cur_sym }}{{ showAmount($donor->donation) }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
#myTabContent .heading_title, #myTabContent .heading_title strong{
    font-size: 1.6rem;
    line-height: 1;
    font-family: "Roboto", sans-serif;
}
.wpb_text_column.wpb_content_element, .softlab_module_spacing{
    margin: 1rem 0 ;
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

@media (max-width:480px){
  .pt-120{
    padding-top: 2rem;
  }

.nav-tabs .nav-item {
    min-width: 100% !important;
    margin: 0;
    padding: 10px;
    text-align: center;
 }

}
@media (max-width:767px){

 .nav-tabs .nav-item {
    min-width: 50% !important;
    margin: 0;
    padding: 10px;
    text-align: center;
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

//        $('#checkdon').on('change', function() {
//            var status = this.checked;
//            if (status) {
//                $('.checktoggle').prop("disabled", true)
//                console.log("checkeeed");
//                $('input[name=name]').val('');
//                $('input[name=email]').val('');
//                $('input[name=mobile]').val('');
//                $('input[name=country]').val('');
//            } else {
//                @if($user)
//                    let user=@json($user);
//                    $('input[name=name]').val(user.firstname+' '+user.lastname);
//                    $('input[name=email]').val(user.email);
//                    $('input[name=mobile]').val(user.mobile);
//                    $('input[name=country]').val(user.address.country);
//                @endif
//                $('.checktoggle').prop("disabled", false)
//            }
//        })


        $(".progressbar").each(function() {
            $(this).find(".bar").animate({
                "width": $(this).attr("data-perc")
            }, 3000);
            $(this).find(".label").animate({
                "left": $(this).attr("data-perc")
            }, 3000);
        });

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

        //donor list
        $('.donarModal').click(function() {
            $('#modelId').modal('show')
        })


    </script>
@endpush
@push('script')
    <script>
        $('.editBtn').on('click', function () {
            var data = $(this).data('resource');
            var isChecked = data['accept_marketing'];
            var checkbox = document.getElementById('checkbox');
            if(checkbox && isChecked == 1){
               checkbox.checked = true;
            }
            else{
              checkbox.checked = false;
            }

            // You can use the 'isChecked' value to perform actions in your modal.
        });
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
                if ($dataV === 100) {
                    $this.addClass("full_percentage");
                    $round.css("clip","auto");
                }
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


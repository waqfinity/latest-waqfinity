@php
    $footer = getContent('footer.content', true);

    $socialIcons = getContent('social_icon.element', false, null, true);
    $policyPages = getContent('policy_pages.element');
    $subscribe   = getContent('subscribe.content', true);
    $contact     = getContent('contact_us.content', true);

    $donation      = App\Models\Donation::paid()->get();
    $donationCount = $donation->count();
    $donationSum   = $donation->sum('donation');

    $countCampaign = App\Models\Campaign::running()->whereDate('deadline', '>', now())->count();
    $categories    = App\Models\Category::active()->hasCampaigns()->orderBy('id', 'DESC')->take(4)->get();
@endphp

<!-- footer section start -->
<footer class="footer-section base--bg position-relative bg_img"
    data-background="{{ getImage('assets/images/frontend/footer/' . $footer->data_values->image, '730x465') }}">
<!--    <div class="top-shape"><img
            src="{{ getImage($activeTemplateTrue . 'images/top_texture.png') }}"></div>-->
    <div class="footer-top">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-2 mb-lg-0 mb-5 text-lg-left text-center">
                    <a href="#0" class="footer-logo">
                        <img src="{{ getImage(getFilePath('logoIcon') . '/' . 'logo.png') }}"
                            alt="@lang('image')"></a>
                </div>
                <div class="col-lg-7 col-md-12 mb-4">
                    <div class="row justify-content-center gy-4 align-items-center">
                        <div class="col-lg-4 col-4 footer-overview-item text-md-left text-center">
                            <h3 class="text-white amount-number text-center">{{ $donationCount }}</h3>
                            <p class="text-white text-center">@lang('Total Donate Members')</p>
                        </div>
                        <div class="col-lg-4 col-4 footer-overview-item text-md-left text-center">
                            <h3 class="text-white amount-number text-center">{{ $countCampaign }}</h3>
                            <p class="text-white text-center">@lang('Total Campaigns')</p>
                        </div>

                        <div class="col-lg-4 col-4 footer-overview-item text-md-left text-center">
                            <h3 class="text-white amount-number text-center">
                                {{ $general->cur_sym }}{{ showAmount($donationSum) }}</h3>
                            <p class="text-white text-center">@lang('Donation Raised')</p>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mt-md-0 mt-4">
                    <div class="text-md-right text-center mb-lg-0 mb-4">
                        <a href="{{ url($footer->data_values->button_url) }}"
                            class="btn cmn-btn">{{ __($footer->data_values->button_name) }}</a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row gy-4">
                <div class="col-lg-3 col-md-6 col-sm-8 ">
                    <div class="footer-widget">
                        <h3 class="footer-widget__title">{{ __($footer->data_values->heading) }}</h3>
                        <p>{{ __($footer->data_values->subheading) }}</p>
                        <ul class="social-links mt-4">
                            @foreach($socialIcons as $icon)
                                <li class="bg-transparent">
                                    <a href="{{ $icon->data_values->url }}" target="_blank">
                                        @php echo $icon->data_values->social_icon; @endphp
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div><!-- footer-widget end -->
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 ">
                    <div class="footer-widget">
                        <h3 class="footer-widget__title">@lang('Categories')</h3>
                        <ul class="short-link-list">
                            @foreach($categories as $category)
                                <li><a href="{{ route('campaign.index', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div><!-- footer-widget end -->
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 ">
                    <div class="footer-widget">
                        <h3 class="footer-widget__title">@lang('Fast Links')</h3>
                        <ul class="short-link-list">
                            <li><a href="{{ route('user.register') }}">@lang('Join Us')</a></li>
                            <li><a href="{{ route('success.story.archive') }}">@lang('Our Success Stories')</a></li>
                            <li><a href="{{ route('campaign.index') }}">@lang('Donate')</a></li>
                            @foreach($pages as $data)
                                <li><a href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div><!-- footer-widget end -->
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <h3 class="footer-widget__title">{{ __($subscribe->data_values->heading) }}</h3>
                        <p>{{ __($subscribe->data_values->subheading) }}</p>
                        <form class="subscribe-form mt-3" method="POST"
                            action="{{ route('subscribe') }}">
                            @csrf
                            <input type="email" name="email" placeholder="@lang('Email Address')" class="form-control"
                                autocomplete="off">
                            <button class="subscribe-btn"><i class="las la-arrow-right"></i></button>
                        </form>
                    </div><!-- footer-widget end -->
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <hr>
            <div class="row">
                <div class="col-lg-8 col-md-6 text-md-start text-center">
                    <p>@lang('Copyright') &copy; {{ date('Y') }} |
                        @lang('All Rights Reserved')</p>
                </div>
                <div class="col-lg-4 col-md-6 mt-md-0">
                    <ul class="link-list justify-content-md-end justify-content-center">
                        @foreach($policyPages as $policy)
                            <li><a href="{{ route('policy.pages', ['slug' => slug($policy->data_values->title), 'id' => $policy->id]) }}">{{ __($policy->data_values->title) }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer section end -->

@push('script')
    <script>
        'use strict';

        $(function () {

            $('.subscribe-form').on('submit', function (event) {
                event.preventDefault();
                let url = `{{ route('subscribe') }}`;

                let data = {
                    email: $(this).find('input[name=email]').val()
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.post(url, data, function (response) {
                    if (response.errors) {
                        for (var i = 0; i < response.errors.length; i++) {
                            iziToast.error({
                                message: response.errors[i],
                                position: "topRight"
                            });
                        }
                    } else {
                        $('.subscribe-form').trigger("reset");
                        iziToast.success({
                            message: response.success,
                            position: "topRight"
                        });
                    }
                });
                this.reset();
            })

        })

    </script>
@endpush

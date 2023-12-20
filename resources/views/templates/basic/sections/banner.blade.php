@php
    $banners = getContent('banner.element', null, false, true);
    $campaignWithCategory = App\Models\Category::active()->orderBy('id', 'DESC')->get();
@endphp



<section class="hero">
    <div class="hero__slider">
        @foreach ($banners as $item)
            <div class="single__slide bg_img"
                data-background="{{ getImage('assets/images/frontend/banner/' . $item->data_values->image, '1980x1280') }}">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="hero__content text-center">
                                <h2 class="hero__title" data-animation="fadeInUp" data-delay=".3s">
                                    {{ __($item->data_values->heading) }} </h2>
                                <p data-animation="fadeInUp" data-delay=".5s"> {{ __($item->data_values->subheading) }}
                                </p>
                                <div class="btn-group mt-40" data-animation="fadeInUp" data-delay=".7s">
                                    <a href="{{ route('user.onboard') }}" class="cmn-btn">
                                        {{ __($item->data_values->button_text_one) }} </a>
                                    <a href="{{ $item->data_values->button_url_two }}" class="cmn-btn">
                                        {{ __($item->data_values->button_text_two) }} </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<div class="banner-slider">
    <div class="container">
        <div class="justify-content-center">
            <div class="responsive">
                @foreach ($campaignWithCategory as $category)
                    <div class="category-card has-link hover--effect-1 js-tilt {{ $loop->iteration % 3 == 0 ? 'overlay--three' : ($loop->odd ? 'overlay--one' : 'overlay--two') }}"data-tilt-perspective="300" data-tilt-speed="400" data-tilt-max="25">
                        <a href="{{ route('campaign.index', ['slug' => $category->slug]) }}" class="item-link"></a>
                        <div class="category-card__thumb">
                            <img src="{{ getImage(getFilePath('category') . '/' . $category->image, getFileSize('category')) }}" class="w-100 __abc">
                        </div>
                        <div class="category-card__content">
                            <h4 class="title text-white">{{ __($category->name) }}</h4>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
 <div class="modal fade" id="openWaqfModal" tabindex="-1" role="dialog" aria-labelledby="startWaqfModalBtnLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="startWaqfModalLabel">Start Waqf</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
                </div>
                @include('templates.basic.user.campaign.campaign-form')
            </div>
        </div>
    </div>

@push('script')
    <script>
        (function($){
            "use strict";
            $('.startWaqfModalBtn').on('click', function () {
                var modal = $('#openWaqfModal');
                var url = $(this).data('url');
                var lang = $(this).data('lang');
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush


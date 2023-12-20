@php
    $counterContent = getContent('counter.content', true);
    $counters = getContent('counter.element', limit: 4, orderById: true);
@endphp
<section>
    <div class="row g-0">
        <div class="col-lg-6 bg_img video-thumb-two min-height--block"
            data-background="{{ getImage('assets/images/frontend/counter/' . $counterContent->data_values->image, '730x465') }}">
            <a class="video-button" href="{{ $counterContent->data_values->video_link }}" type="video/mp4"
                data-rel="lightcase:myCollection"><i class="las la-play"></i></a>
        </div>
        <div class="col-lg-6 pt-120 pb-120 position-relative bg--base text-md-left text-center">
            <div class="section-img"><img src="{{ getImage($activeTemplateTrue . 'images/texture-3.jpg') }}"
                    alt="@lang('image')"></div>
            <div class="overview-area position-relative">
                <h2 class="section-title text-white">{{ __($counterContent->data_values->title) }}</h2>
                <p class="text-white text-justify">{{ __($counterContent->data_values->description) }}</p>
                <div class="row gy-4 mt-50">
                    @foreach ($counters as $i => $counter)
                        <div class="col-xl-3 col-sm-3 col-6 mb-30">
                            <div class="counter-card position-relative z-1">
                                <div class="texture-bg"><img
                                        src="{{ getImage($activeTemplateTrue . 'images/texture-1.png') }}"></div>
                                <div class="counter-card__content">
                                    <span
                                        class="count-num color--{{ $i + 1 }}">{{ $counter->data_values->digit }}</span>
                                    <p class="text-dark">{{ __($counter->data_values->title) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@push('style')
    <style>
        .color--1 {
            color: #13c366 !important;
        }

        .color--2 {
            color: #f32424 !important;
        }

        .color--3 {
            color: #b013c3 !important;
        }

        .color--4 {
            color: #1178d0 !important;
        }
    </style>
@endpush

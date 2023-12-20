@php
    $content = getContent('cta.content', true);
@endphp
<section class="pt-120 pb-120 position-relative bg_img overlay-one" data-background="{{ getImage('assets/images/frontend/cta/' . $content->data_values->image) }}">
    <!--<div class="bottom-shape"><img src="{{ asset($activeTemplateTrue . 'images/top-shape.png') }}" alt="@lang('image')"></div>-->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center pb-90">
                <h2 class="text-white">{{ __($content->data_values->heading) }}</h2>
                <p class="text-white">{{ __($content->data_values->subheading) }}</p>
                <a href="{{ $content->data_values->button_url }}" class="cmn-btn my-5">{{ __($content->data_values->button_title) }}</a>
            </div>
        </div>
    </div>
</section>

@php
    $content = getContent('breadcrumb.content', true);
@endphp

<section class="inner-page-hero bg_img" data-background="{{ getImage('assets/images/frontend/breadcrumb/' . @$content->data_values->image, '730x465') }}">
    <!--<div class="bottom-shape"><img src="{{ asset($activeTemplateTrue . 'images/top-shape.png') }}" alt="@lang('image')"></div>-->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h2 class="page-title">{{ __($pageTitle) }}</h2>
            </div>
        </div>
    </div>
</section>

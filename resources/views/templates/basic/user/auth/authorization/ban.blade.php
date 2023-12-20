@extends($activeTemplate . 'layouts.app')
@section('panel')
    @php
        $banned = getContent('banned.content', true);
    @endphp
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="row justify-content-center">
                    <div class="col-sm-6 col-8 col-lg-12 mt-3">
                        <img src="{{ getImage('assets/images/frontend/banned/' . @$banned->data_values->image, '700x400') }}"
                            alt="@lang('image')" class="img-fluid mx-auto">
                    </div>
                </div>
                <h2 class="text-danger">{{ __(@$banned->data_values->heading) }}</h2>
                <div>
                    <h4>@lang('Ban Reason')</h4>
                    <span class="text-danger mb-3"> {{ __(auth()->user()->ban_reason) }}</span>
                </div>
                <a href="{{ route('home') }}" class="cmn-btn">@lang('Go To Home')</a>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function ($) {
            $('header').remove();
            $('.header__top').remove();
            $('.inner-page-hero').remove();
            $('footer').remove();
        })(jQuery);

    </script>
@endpush
@push('style')
<style>
    body{
        display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
    }
</style>
@endpush

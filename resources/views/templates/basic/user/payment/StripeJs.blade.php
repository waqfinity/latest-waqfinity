@extends($activeTemplate.'layouts.master')
@section('content')
<section class="mb-4 mt-4">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card custom--shadow">
                <div class="card-header text-center base--bg p-3">
                    <span>@lang('Stripe Storefront')</span>
                </div>
                <div class="card-body">
                    <form action="{{$data->url}}" method="{{$data->method}}">
                        <ul class="list-group text-center">
                            <li class="list-group-item d-flex justify-content-between">
                                @lang('You have to pay '):
                                <strong>{{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                @lang('You will get '):
                                <strong>{{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</strong>
                            </li>
                        </ul>
                         <script src="{{$data->src}}"
                            class="stripe-button cmn-btn"
                            @foreach($data->val as $key=> $value)
                            data-{{$key}}="{{$value}}"
                            @endforeach >
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
@push('script-lib')
    <script src="https://js.stripe.com/v3/"></script>
@endpush
@push('script')
    <script>
        (function ($) {
            "use strict";
            $('button[type="submit"]').addClass("btn cmn-btn w-100 mt-3");
            $('button[type="submit"]').text("Pay Now");
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .stripe-button-el{
            background-image: unset !important;
            padding: 14px 35px !important;
        }
        .stripe-button-el:not(:disabled):active, .stripe-button-el.active, .stripe-button-el:focus {
    background: #0e954e !important;
    box-shadow: none !important;
}
    </style>
@endpush

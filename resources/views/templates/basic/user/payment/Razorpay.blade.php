@extends($activeTemplate.'layouts.master')

@section('content')
<section class="mb-4 mt-4">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card custom--shadow">
                <div class="card-header text-center base--bg p-3">
                    <span>@lang('Razorpay')</span>
                </div>
                <div class="card-body">
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
                     <form action="{{$data->url}}" method="{{$data->method}}">
                        <input type="hidden" custom="{{$data->custom}}" name="hidden">
                        <script src="{{$data->checkout_js}}"
                                @foreach($data->val as $key=>$value)
                                data-{{$key}}="{{$value}}"
                            @endforeach >
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="mb-4 mt-4">
@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";
            $('input[type="submit"]').addClass("mt-4 btn cmn-btn w-100");
        })(jQuery);
    </script>
@endpush

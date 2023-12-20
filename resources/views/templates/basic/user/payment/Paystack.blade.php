@extends($activeTemplate.'layouts.master')
@section('content')
<section class="mb-4 mt-4">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card custom--shadow">
                <div class="card-header text-center  base--bg p-3">
                    <span>@lang('Paystack')</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('ipn.'.$deposit->gateway->alias) }}" method="POST" class="text-center">
                        @csrf
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
                        <button type="button" class="btn cmn-btn w-100 mt-3" id="btn-confirm">@lang('Pay Now')</button>
                        <script
                            src="//js.paystack.co/v1/inline.js"
                            data-key="{{ $data->key }}"
                            data-email="{{ $data->email }}"
                            data-amount="{{ round($data->amount) }}"
                            data-currency="{{$data->currency}}"
                            data-ref="{{ $data->ref }}"
                            data-custom-button="btn-confirm"
                        >
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection

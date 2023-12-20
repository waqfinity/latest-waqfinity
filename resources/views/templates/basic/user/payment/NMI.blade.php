@extends($activeTemplate.'layouts.master')
@section('content')
<section class="mb-4 mt-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card custom--shadow">
                    <div class="card-header text-center base--bg p-3">
                        <span>@lang('NMI')</span>
                    </div>
                    <div class="card-body">
                        <form role="form" id="payment-form" method="{{$data->method}}" action="{{$data->url}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">@lang('Card Number')</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control form--control" name="billing-cc-number" autocomplete="off" value="{{ old('billing-cc-number') }}" required autofocus/>
                                        <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label class="form-label">@lang('Expiration Date')</label>
                                    <input type="tel" class="form-control form--control" name="billing-cc-exp" value="{{ old('billing-cc-exp') }}" placeholder="e.g. MM/YY" autocomplete="off" required/>
                                </div>
                                <div class="col-md-6 ">
                                    <label class="form-label">@lang('CVC Code')</label>
                                    <input type="tel" class="form-control form--control" name="billing-cc-cvv" value="{{ old('billing-cc-cvv') }}" autocomplete="off" required/>
                                </div>
                            </div>
                            <br>
                            <button class="btn cmn-btn w-100" type="submit"> @lang('Submit')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

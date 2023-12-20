@extends($activeTemplate.'layouts.frontend')

@section('content')
<section class="mb-4 mt-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card custom--shadow">
                    <div class="card-header text-center base--bg p-3">
                        <span>{{__($pageTitle)}}</span>
                    </div>
                    <div class="card-body  ">
                        <form action="{{ route('deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <p class="text-center mt-2">@lang('You have requested') <b class="text-success">{{ showAmount($data['amount'])  }} {{__($general->cur_text)}}</b> , @lang('Please pay')
                                        <b class="text-success">{{showAmount($data['final_amo']) .' '.$data['method_currency'] }} </b> @lang('for successful payment')
                                    </p>
                                    <h4 class="text-center mb-4">@lang('Please follow the instruction below')</h4>
                                    <p class="my-4 text-center">@php echo  $data->gateway->description @endphp</p>
                                </div>

                              <x-viser-form identifier="id" identifierValue="{{ $gateway->form_id }}" />

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn cmn-btn w-100">@lang('Pay Now')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

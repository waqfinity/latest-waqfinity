@extends($activeTemplate .'layouts.frontend')
@section('content')
<section class="pt-120 pb-120">
<div class="container">
    <div class="d-flex justify-content-center">
        <div class="verification-code-wrapper custom--shadow">
            <div class="verification-area">
                <h5 class="pb-3 text-center border-bottom">@lang('2FA Verification')</h5>
                <form action="{{route('user.go2fa.verify')}}" method="POST" class="submit-form">
                    @csrf

                    @include($activeTemplate.'partials.verification_code')

                    <div class="form--group">
                        <button type="submit" class="btn cmn-btn w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</section>
@endsection

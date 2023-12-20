@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-120 pb-120">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper custom--card">
                    <div class="verification-area">
                        <form action="{{ route('user.password.verify.code') }}" method="POST" class="submit-form">
                            @csrf
                            <p class="verification-text">@lang('A 6 digit verification code sent to your email address') : {{ showEmailAddress($email) }}</p>
                            <input type="hidden" name="email" value="{{ $email }}">

                            @include($activeTemplate . 'partials.verification_code')

                            <div class="form-group">
                                <button type="submit" class="btn cmn-btn w-100">@lang('Submit')</button>
                            </div>

                            <div class="form-group">
                                @lang('Please check including your Junk/Spam Folder. if not found, you can')
                                <a href="{{ route('user.password.request') }}">@lang('Try to send again')</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

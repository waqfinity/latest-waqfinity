@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $policyPages = getContent('policy_pages.element', false, null, true);
        $register = getContent('register.content', true);
    @endphp
    <section class="pt-90 pb-120">
        <div class="container">
            <div class="row g-0 justify-content-center login-registration-box">
                <div class="col-md-6 pr-0 pl-0">
                    <div class="content-area bg_img"
                        data-background="{{ getImage('assets/images/frontend/register/' . @$register->data_values->image, '1024x720') }}">
                    </div>
                </div>

                <div class="col-lg-6 p-sm-0">
                    <div class="p-sm-5 p-4 custom--shadow">
                        <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha action-form">
                            @csrf
                            <div class="login-area text-center pb-2">
                                <h2 class="title">{{ __(@$register->data_values->heading) }}</h2>
                                <p>{{ __(@$register->data_values->subheading) }}</p>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Username')</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-text"><i class="las la-user"></i></div>
                                            <input type="text" class="form-control checkUser" name="username"
                                                value="{{ old('username') }}" required>
                                        </div>
                                        <small class="text-danger usernameExist"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('E-Mail')</label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="las la-envelope"></i></div>
                                            <input type="email" class="form-control checkUser" name="email"
                                                value="{{ old('email') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Country')</label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="las la-globe"></i></div>
                                            <select name="country" class="form-control" required>
                                                @foreach ($countries as $key => $country)
                                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">
                                                        {{ __($country->country) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Mobile')</label>
                                        <div class="input-group ">
                                            <span class="input-group-text mobile-code">

                                            </span>
                                            <input type="hidden" name="mobile_code">
                                            <input type="hidden" name="country_code">
                                            <input type="number" name="mobile" value="{{ old('mobile') }}"
                                                class="form-control checkUser" required>
                                        </div>
                                        <small class="text-danger mobileExist"></small>
                                    </div>
                                </div>

                                <div class="col-md-6 form-group ">
                                    <label class="form-label">@lang('Password')</label>
                                        <div class="input-group overflow-visible">
                                            <span class="input-group-text"><i class="las la-key"></i></span>
                                            <input type="password" class="form-control" name="password" required>
                                            @if ($general->secure_password)
                                                <div class="input-popup">
                                                    <p class="error lower">@lang('1 small letter minimum')</p>
                                                    <p class="error capital">@lang('1 capital letter minimum')</p>
                                                    <p class="error number">@lang('1 number minimum')</p>
                                                    <p class="error special">@lang('1 special character minimum')</p>
                                                    <p class="error minimum">@lang('6 character password')</p>
                                                </div>
                                            @endif
                                        </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Confirm Password')</label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="las la-key"></i></div>
                                            <input type="password" class="form-control" name="password_confirmation"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <x-captcha />

                            </div>
                            @if ($general->agree)
                            <div class="d-flex align-items-center justify-content-start flex-wrap text-start">
                                <div class="form-group">
                                    <input type="checkbox" name="agree" id="agree" @checked(old('agree'))
                                    required>
                                <label for="agree">@lang('I agree with') </label>
                                    @foreach ($policyPages as $policy)
                                    <a
                                        href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}">{{ __($policy->data_values->title) }}</a>
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            @endif
                            <div class="form-group text-center">
                                <button type="submit" class="btn cmn-btn w-100 ">@lang('Register')</button>
                            </div>
                            <p class="text-center">@lang('Already have an account')?<a
                                    href="{{ route('user.login') }}">&nbsp; @lang('Login')</a> </p>
                        </form>
                        @php
                            $credentials = $general->socialite_credentials;
                        @endphp
                        @if (
                            $credentials->google->status == Status::ENABLE ||
                                $credentials->facebook->status == Status::ENABLE ||
                                $credentials->linkedin->status == Status::ENABLE)

                        <div class="registration-socails__content text-center">
                            <p class="registration-socails__desc mb-0 mt-0"> @lang('Or Login with') </p>
                        </div>

                            <div class="d-flex flex-wrap gap-3">
                                @if ($credentials->facebook->status == Status::ENABLE)
                                    <a href="{{ route('user.social.login', 'facebook') }}"
                                        class="btn btn-outline-facebook btn-sm flex-grow-1">
                                        <span class="me-1"><i class="fab fa-facebook-f"></i></span> @lang('Facebook')
                                    </a>
                                @endif
                                @if ($credentials->google->status == Status::ENABLE)
                                    <a href="{{ route('user.social.login', 'google') }}"
                                        class="btn btn-outline-google btn-sm flex-grow-1">
                                        <span class="me-1"><i class="lab la-google-plus-g"></i></span> @lang('Google')
                                    </a>
                                @endif
                                @if ($credentials->linkedin->status == Status::ENABLE)
                                    <a href="{{ route('user.social.login', 'linkedin') }}"
                                        class="btn btn-outline-linkedin btn-sm flex-grow-1">
                                        <span class="me-1"><i class="lab la-linkedin-in"></i></span> @lang('Linkedin')
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                        <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </span>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-center pb-3">@lang('You already have an account please Login').</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark btn-sm btn-lg"
                            data-bs-dismiss="modal">@lang('Close')</button>
                        <a href="{{ route('user.login') }}" class="btn cmn-btn btn-sm">@lang('Login')</a>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
@push('style')
    <style>
        .country-code select:focus {
            border: none;
            outline: none;
        }

        .content-area {
            z-index: -1;
            height: 100%;
        }

        .btn-outline-linkedin {
            border-color: #0077B5;
            background-color: transparent;
            color: #0077B5;
        }

        .btn-outline-linkedin:hover {
            border-color: #0077B5;
            color: #fff !important;
            background-color: #0077B5;
        }

        .btn-outline-facebook {
            border-color: #395498;
            background-color: transparent;
            color: #395498;
        }

        .btn-outline-facebook:hover {
            border-color: #395498;
            color: #fff !important;
            background-color: #395498;
        }

        .btn-outline-google {
            border-color: #D64937;
            background-color: transparent;
            color: #D64937;
        }

        .btn-outline-google:hover {
            border-color: #D64937;
            color: #fff !important;
            background-color: #D64937;
        }

    </style>
@endpush
@if ($general->secure_password)
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
@push('script')
    <script>
        "use strict";
        (function($) {

            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            $('select[name=country]').change(function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false && response.type == 'email') {
                        $('#existModalCenter').modal('show');
                    } else if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.type} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });

                $("#agree").closest("label").removeClass('required');
            });
        })(jQuery);
    </script>
@endpush

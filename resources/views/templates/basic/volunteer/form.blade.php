@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <!-- login section start -->
    <section class="pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 custom--shadow p-5">
                    <div class="login-area">
                        <form class="action-form" action="{{ route('volunteer.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 text-center ">
                                    <div class="form-group">
                                        <label>@lang('Upload Your Image') </label>
                                        <div class="profile-thumb justify-content-center">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview"
                                                    style="background-image: url('{{ getImage('', getFileSize('volunteer')) }}');">
                                                </div>

                                                <div class="avatar-edit">
                                                    <input type='file' class="profilePicUpload" name="image"
                                                        id="profilePicUpload1" accept=".png, .jpg, .jpeg" required />
                                                    <label for="profilePicUpload1" class="btn btn--base mb-0">
                                                        <i class="la la-camera"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('First Name')</label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="las la-user"></i></div>
                                            <input type="text" class="form-control" name="firstname"
                                                value="{{ old('firstname') }}" required />
                                        </div>
                                    </div>
                                </div><!-- form-group end -->
                                <div class="col-sm-6">
                                    <div class="form-group ">
                                        <label>@lang('Last Name')</label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="las la-user"></i></div>
                                            <input type="text" class="form-control" name="lastname"
                                                value="{{ old('lastname') }}" required>
                                        </div>
                                    </div>
                                </div><!-- form-group end -->
                                <div class="col-sm-6">
                                    <div class="form-group ">
                                        <label>@lang('Email')</label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="las la-envelope"></i></div>
                                            <input type="text" class="form-control" name="email"
                                                value="{{ old('email') }}" required>
                                        </div>
                                    </div>
                                </div><!-- form-group end -->
                                <div class="col-sm-6">
                                    <div class="form-group ">
                                        <label>@lang('Country')</label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="las la-globe"></i></div>
                                            <select name="country" class="form-control" required>
                                                @foreach ($countries as $key => $country)
                                                    <option data-mobile_code="{{ $country->dial_code }}"
                                                        value="{{ $country->country }}" data-code="{{ $key }}">
                                                        {{ __($country->country) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Mobile')</label>
                                        <div class="input-group ">
                                            <span class="input-group-text mobile-code"></span>
                                            <input type="hidden" name="mobile_code">
                                            <input type="hidden" name="country_code">
                                            <input type="number" name="mobile" id="mobile" value="{{ old('mobile') }}"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('State')</label>
                                        <div class="input-group">
                                            <div class="input-group-text"> <i class="las la-flag"></i></div>
                                            <input type="text" name="state" class="form-control" required>
                                        </div>
                                    </div>
                                </div><!-- form-group end -->
                                <div class="form-group col-md-6">
                                    <label>@lang('Zip')</label>
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="las la-sort-numeric-up-alt"></i> </div>
                                        <input type="text" name="zip" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>@lang('City')</label>
                                    <div class="input-group">
                                        <div class="input-group-text"> <i class="las la-city"></i></div>
                                        <input type="text" name="city" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>@lang('Address')</label>
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="las la-map-marked"></i></div>
                                        <input type="text" name="address" class="form-control" required>
                                    </div>
                                </div><!-- form-group end -->
                            </div>
                            <input type="submit" class="btn cmn-btn w-100">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- login section end -->
@endsection

@push('style')
    <style>
        .profile-thumb {
            display: flex;
            align-items: center;
        }

        @media (max-width: 520px) {
            .profile-thumb {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 520px) {
            .avatar-edit {
                width: 100%;
                padding-left: 0 !important;
                margin-top: 0.9375rem;
            }
        }

        .avatar-preview {
            width: 11.25rem;
            height: 11.25rem;
            border-radius: 15px;
            -webkit-border-radius: 15px;
            -moz-border-radius: 15px;
            -ms-border-radius: 15px;
            -o-border-radius: 15px;
            display: block;
            position: relative;
        }

        .avatar-preview .profilePicPreview {
            width: 11.25rem;
            height: 11.25rem;
            border-radius: 15px;
            -webkit-border-radius: 15px;
            -moz-border-radius: 15px;
            -ms-border-radius: 15px;
            -o-border-radius: 15px;
            display: block;
            background-size: cover;
            background-position: center;
        }

        .avatar-preview .btn--base {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            position: absolute;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            background-color: hsl(var(--base));
            color: #fff;
            right: -5px;
            bottom: -5px;
            border: 4px solid #fff;
        }

        .profilePicUpload {
            font-size: 0;
            opacity: 0;
            width: 0;
        }
    </style>
@endpush

@push('script')
    <script>
        $(function() {
            "use strict";
            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif
            $('select[name=country]').change(function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            }).change();

            function companyProfilePhoto(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var preview = $(input).parents('.profile-thumb').find('.profilePicPreview');
                        $(preview).css('background-image', 'url(' + e.target.result + ')');
                        $(preview).addClass('has-image');
                        $(preview).hide();
                        $(preview).fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $(".profilePicUpload").on('change', function() {
                companyProfilePhoto(this);
            });

            const requiredClass = document.querySelector('.btn--base');
            requiredClass.classList.remove("required");

        })
    </script>
@endpush

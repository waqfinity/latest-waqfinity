@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-xl-3 col-lg-5 col-md-5 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body p-0">
                    <div class="p-3 bg--white">
                        <div class="">
                            <img src="{{ getImage(getFilePath('volunteer') . '/' . $volunteer->image, getFileSize('volunteer')) }}"
                                alt="profile-image" class="b-radius--10 w-100">
                        </div>
                        <div class="mt-15">
                            <h4 class="">{{ $volunteer->fullname }}</h4>
                            <span class="text--small">@lang('Joined At ')<strong>{{ showDateTime($volunteer->created_at, 'd M, Y h:i A') }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Notification')</h5>
                    <a href="{{ route('admin.volunteer.email', $volunteer->id)}}" class="btn btn--warning btn--shadow w-100 btn-lg">
                        <i class="las la-paper-plane"></i> @lang('Send Email')
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 mb-0">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.volunteer.update', $volunteer->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>@lang('First Name')</label>
                                    <input class="form-control" type="text" name="firstname"
                                        value="{{ $volunteer->firstname }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Last Name')</label>
                                    <input class="form-control" type="text" name="lastname"
                                        value="{{ $volunteer->lastname }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>@lang('Email')</label>
                                    <input class="form-control" type="email" name="email"
                                        value="{{ $volunteer->email }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Mobile Number')</label>
                                    <div class="input-group ">
                                        <span class="input-group-text mobile-code"></span>
                                        <input type="number" name="mobile" value="{{ old('mobile', $volunteer->mobile) }}" id="mobile"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>@lang('Address') <i class="las la-info-circle" title=" @lang('House number, street address')"></i></label>
                                    <input class="form-control" type="text" name="address"
                                        value="{{ $volunteer->address->address }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>@lang('Participation Campaign') </label>
                                    <input class="form-control" type="text" name="participation"
                                        value="{{ $volunteer->participated }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label>@lang('City') </label>
                                    <input class="form-control" type="text" name="city"
                                        value="{{ $volunteer->address->city }}" required>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>@lang('State') </label>
                                    <input class="form-control" type="text" name="state"
                                        value="{{ $volunteer->address->state }}" >
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>@lang('Zip/Postal') </label>
                                    <input class="form-control" type="text" name="zip"
                                        value="{{ $volunteer->address->zip }}" required>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>@lang('Country') </label>
                                    <select name="country" class="form-control" required>
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}"
                                                value="{{ $key }}">{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')
                                    </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
<x-back route="{{ route('admin.volunteer.index')}}"/>
@endpush

@push('script')
    <script>
        'use strict';
        let mobileElement = $('.mobile-code');
        $('select[name=country]').change(function() {
            mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
        });

        $('select[name=country]').val('{{ @$volunteer->country_code }}');
        let dialCode = $('select[name=country] :selected').data('mobile_code');
        let mobileNumber = `{{ $volunteer->mobile }}`;
        mobileNumber = mobileNumber.replace(dialCode, '');
        $('input[name=mobile]').val(mobileNumber);
        mobileElement.text(`+${dialCode}`);
    </script>
@endpush

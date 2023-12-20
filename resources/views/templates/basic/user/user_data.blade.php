@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <section class="pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card custom--card mb-4">
                        <div class="card-body">
                        <h3>@lang('Complete Your Profile')</h3>
                        <p>@lang('You must complete your profile by providing the required information').</p>
                        </div>
                    </div>
                    <div class="login-area card custom--card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('user.data.submit') }}" class="action-form">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('First Name')</label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="las la-user"></i></div>
                                                <input type="text" class="form-control form--control" name="firstname" value="{{ old('firstname') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Last Name')</label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="las la-user"></i></div>
                                                <input type="text" class="form-control form--control" name="lastname" value="{{ old('lastname') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group ">
                                            <label class="form-label">@lang('Address')</label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="las la-map-marked"></i></div>
                                                <input type="text" class="form-control form--control" name="address" value="{{ old('address') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group ">
                                            <label class="form-label">@lang('State')</label>
                                            <div class="input-group">
                                                <div class="input-group-text"> <i class="las la-flag"></i></div>
                                                <input type="text" class="form-control form--control" name="state" value="{{ old('state') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Zip Code')</label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="las la-sort-numeric-up-alt"></i> </div>
                                                <input type="text" class="form-control form--control" name="zip" value="{{ old('zip') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('City')</label>
                                            <div class="input-group">
                                                <div class="input-group-text"> <i class="las la-city"></i></div>
                                                <input type="text" class="form-control form--control" name="city" value="{{ old('city') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn cmn-btn w-100">
                                        @lang('Submit')
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@php
    $contact = getContent('contact_us.content', true);
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="pt-90 pb-120">
        <div class="container">
            <div class="card custom--shadow p-3">
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-7">
                        <div class="card-body">
                            <h2 class="mb-1">{{ __($contact->data_values->heading) }}</h2>
                            <form method="POST" action="" class="verify-gcaptcha action-form">
                                @csrf
                                @php $name = old('name', auth()->user()->fullname ?? null); @endphp
                                <div class="form-group">
                                    <div class="input-group">
                                        <input name="name" type="text" class="form-control"
                                            value="{{ $name }}" @if (auth()->user()) readonly @endif placeholder="@lang('Your name')"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input name="email" type="email" class="form-control"
                                            value="@if (auth()->user()) {{ auth()->user()->email }}@else{{ old('email') }} @endif"
                                            @if (auth()->user()) readonly @endif placeholder="@lang('Email')" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input name="subject" type="text" class="form-control" value="{{ old('subject') }}" placeholder="@lang('Subject')" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea name="message" wrap="off" class="form-control" placeholder="@lang('Write your message')" required>{{ old('message') }}</textarea>
                                </div>

                                <x-captcha isCustom="true" />

                                <button type="submit" class="btn cmn-btn w-30 ">@lang('Create Ticket')</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-5 ps-lg-4 ps-xl-5">
                        <div class="contacts-info">
                            <img src="{{ asset('assets/images/frontend/contact_us/' . $contact->data_values->image) }}"
                                class="contact-img mb-4" alt="image">
                            <div class="address row gy-4">
                                <div class="location col-12">
                                    <div class="contact-card">
                                        <span class="icon"><i class="las la-map-marker"></i></span>
                                        <span>{{ __($contact->data_values->contact_address) }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="contact-card">
                                        <span class="icon"><i class="las la-phone-volume"></i></span>
                                        <a href="tel:{{ $contact->data_values->contact_number }}">{{ __($contact->data_values->contact_number) }}</a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="contact-card">
                                        <span class="icon"><i class="las la-envelope-open"></i></span>
                                        <a href="mailto:{{ $contact->data_values->contact_email }}">{{ __($contact->data_values->contact_email) }}</a>
                                    </div>
                                </div>
                            </div>

                            <ul class="social-links mt-4">
                                <li class="facebook face">
                                    <a target="_blank" href="{{@$contact->data_values->facebook_link}}">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                 </li>
                                <li class="twitter twi">
                                    <a target="_blank" href="{{@$contact->data_values->twitter_link}}"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li class="linkedin lin">
                                    <a target="_blank"  href="{{@$contact->data_values->linkedin_link}}"><i  class="fab fa-linkedin-in"></i></a>
                                </li>                                
                                <li class="linkedin lin" style="background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);">
                                    <a target="_blank"  href="https://www.instagram.com/waqfinity/"><i  class="fab fa-instagram"></i></a>
                                </li>
                                 <li class="linkedin lin" style="background:#CD201F;">
                                    <a target="_blank"  href="https://www.youtube.com/@waqfinity5820"><i  class="fab fa-youtube"></i></a>
                                </li>                      
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
       .contact-card a {
            color: unset !important;
        }
        .cmn-btn{
            border-radius: 4px;
        }
    </style>
@endpush

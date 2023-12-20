@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-xl-12">
            <div class="card">
                <form action="{{ route('admin.volunteer.email.send', $volunteer->id) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="font-weight-bold">@lang('Subject')</label>
                                <input type="text" class="form-control" placeholder="@lang('Email subject')" name="subject"  required/>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="font-weight-bold">@lang('Message')</label>
                                <textarea name="message" rows="10" class="form-control nicEdit" placeholder="@lang('Your message')"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form-row">
                            <div class="form-group col-md-12 text-center">
                                <button type="submit" class="btn btn-block btn--primary w-100 h-45">@lang('Send Email')</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
<x-back route="{{ route('admin.volunteer.details', $volunteer->id)}}"/>
@endpush

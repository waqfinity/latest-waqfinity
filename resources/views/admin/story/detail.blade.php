@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-4 mb-30">
                    <div class="card">
                        <div class="card-body border-bottom">
                            <img src="{{ getImage(getFilePath('success') . '/' . $story->image) }}" alt="profile-image"
                                class="user-image">
                            <h5 class="mt-3"> {{ __($story->title) }}</h5>
                            <ul class="list-group list-group-flush mt-5">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">@lang('Category')</div>
                                    </div>
                                    <span class="badge badge--primary rounded-pill">{{ __(@$story->category->name) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">@lang('Created')</div>
                                    </div>
                                    <span class="badge badge--success rounded-pill">{{ diffForHumans($story->created_at) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">@lang('Total Review')</div>
                                    </div>
                                    <span class="badge badge--warning rounded-pill">{{ $story->comment->count() }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 mb-30">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-25 border-bottom pb-2">@lang('Description of success story')</h5>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <p class="text-justify">
                                        @php
                                            echo $story->description;
                                        @endphp
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.success.story.index') }}" />
@endpush


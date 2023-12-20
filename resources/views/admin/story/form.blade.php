@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.success.story.store', @$story->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview"
                                                    style="background-image: url({{ getImage(getFilePath('success') . '/' . @$story->image, getFileSize('success')) }})">
                                                    <button type="button" class="remove-image"><i
                                                            class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="image"
                                                    id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                <label for="profilePicUpload1" class="bg--primary">@lang('Upload Image')</label>
                                                <small class="mt-2">@lang('Supported files'): <b>@lang('jpeg'),
                                                        @lang('jpg'), @lang('png')</b>. @lang('Image will be resized into')
                                                    {{ getFileSize('success') }}@lang('px'). </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="form-group">
                                    <label>@lang('Categories')</label>
                                    <select class="form-control" name="category"  required>
                                        <option value="" selected disabled>@lang('Select One')</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected($category->id == @$story->category_id)>{{ $category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Title')</label>
                                    <input type="text" class="form-control" name="title" value="{{old('title', @$story->title)}}" required>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Short Description')</label>
                                    <textarea class="form-control" name="short_description" rows="5" required> {{ old('short_description',@$story->short_description)}}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Description')</label>
                                    <textarea class="form-control nicEdit" name="description" rows="8">{{ old('description',@$story->description)}}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="form-control btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{route('admin.success.story.index')}}"/>
@endpush


@push('style')
<style>
    .image-upload .thumb .profilePicUpload { display: none; }
    .avatar-edit{ margin-top: 12px; }
</style>
@endpush

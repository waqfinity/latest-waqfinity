<section class="mt-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 p-lg-5 p-md-4 p-3 card custom--shadow">
                    <div class="login-area">
                        <form action="{{ route('user.campaign.fundrise.store') }}" class="action-form" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                @php 
                                 $pageTitle = 'Create New Waqf Page';
                                 $categories = \App\Models\Category::active()->orderBy('id', 'DESC')->get();
                                @endphp
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Select Category')</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-align-justify"></i></span>
                                            <select name="category_id" class="form-control form--control" required>
                                                <option value="" disabled selected>@lang('Select One')</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                                        {{ __($category->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Goal Amount')</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ $general->cur_sym }}</span>
                                            <input type="number" step="any" name="goal" value="{{ old('goal') }}"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>@lang('Title')</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                    <input type="text" name="title" value="{{ old('title') }}" class="form-control"
                                        required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>@lang('Target Date')</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                    <input name="deadline" type="text" data-language="en"
                                        class="datepicker-here form-control" data-position='bottom left' autocomplete="off"
                                        value="{{ old('deadline') }}" required>
                                </div>
                            </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Banner Image')</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-images"></i></span>
                                            <input type="file" name="image" id="inputAttachments" class="form-control"
                                                accept="image/*" required />
                                        </div>
                                    </div><!-- form-group end -->
                                </div>
                            <div class="form-group">
                                <label>@lang('Description')<span class="text-danger">*</span></label>
                                <textarea class="form-control nicEdit" name="description" rows="8">{{ old('description') }}</textarea>
                                <small>@lang('It can be long text and describe why the campaign was created').</small>
                            </div>
                            <div class="row">

                                <div class="document-file">
                                    <div class="document-file__input">
                                        <div class="form-group">
                                            <label>@lang('Additional Images and Supporting Documents')</label>
                                            <input type="file" name="attachments[]" id="inputAttachments"
                                                class="form-control mb-2" accept=".jpg, .jpeg, .png, .pdf" required />

                                        </div><!-- form-group end -->
                                    </div>
                                    <button type="button" class="btn cmn-btn add-new">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <div id="fileUploadsContainer"></div>
                                    <small class="text-muted mb-2">
                                        @lang('Allowed File Types: .jpg, .jpeg, .png, .pdf')
                                    </small>
                                </div>
                            </div>
                            <button type="submit" class="btn cmn-btn w-100" type="submit">@lang('Create Page')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/datepicker.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/nicEdit.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';

        $(".add-new").on('click', function() {
            $("#fileUploadsContainer").append(` <div class="input-group mb-2">
                <input type="file" name="attachments[]" id="inputAttachments" class="form-control" accept=".jpg, .jpeg, .png, .pdf" required/>
                        <button type="button" class="input-group-text btn--danger remove-btn"><i class="las la-times"></i></button>
                    </div>
                `);
        })

        $(document).on('click', '.remove-btn', function() {
            $(this).closest('.input-group').remove();
        });


        //nicEdit
        $(".nicEdit").each(function(index) {
            $(this).attr("id", "nicEditor" + index);
            new nicEditor({
                fullPanel: true
            }).panelInstance('nicEditor' + index, {
                hasPanel: true
            });
        });

        (function($) {
            $(document).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain', function() {
                $('.nicEdit-main').focus();
            });
        })(jQuery);
    </script>
@endpush


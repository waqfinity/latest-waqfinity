@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 custom--shadow p-4">
                    <div class="login-area">
                        <form id="impactConfigurationForm" action="{{ route('user.campaign.fundrise.store', $campaign->id) }}" class="action-form"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group">
                                    <label>@lang('Select Category')</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-align-justify"></i></span>
                                        <select name="category_id" class="form-control form--control" required>
                                            <option value="" disabled selected>@lang('Select One')</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @selected($campaign->category_id == $category->id)>
                                                    {{ __($category->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Goal')</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ $general->cur_sym }}</span>
                                        <input type="number" step="any" name="goal" value="{{ $campaign->goal }}"
                                            class="form-control" required placeholder="@lang('Your goal')">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Title')</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                        <input type="text" name="title" value="{{ old('title', $campaign->title) }}"
                                            class="form-control" required>
                                    </div>
                                </div><!-- form-group end -->

                                <div class="form-group">
                                    <label>@lang('Deadline')</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="far fa-clock"></i></span>
                                        <input name="deadline" type="text" data-language="en"
                                            class="datepicker-here form-control" data-position='bottom left'
                                            autocomplete="off" value="{{ old('deadline', showDateTime($campaign->deadline,'Y/m/d')) }}" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Description')<span class="text-danger">*</span></label>
                                    <textarea class="form-control nicEdit" name="description" rows="8">{{ old('description', $campaign->description) }}</textarea>
                                    <small>@lang('It can be long text and describe why the campaign was created').</small>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="required">@lang('Donation Category')</label>
                                @csrf
                                @php
                                    $categories = \App\Models\DonationCategory::searchable(['name'])->orderBy('name')->paginate(getPaginate());
                                    $CampaignsDonationCategoryId = \App\Models\CampaignsDonationCategory::where('campaign_id',$campaign->id)->pluck("donation_category_id");

                                @endphp


                                <div class="d-flex align-items-center gap-3 flex-wrap" style="padding-bottom: 20px; border-bottom: 1px solid #eee;" id="aboveCheckboxes">
                                    @foreach ($categories as $category)
                                        @if (__($category->name) !== 'Let Waqfinity Decide')
                                            <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap" style="background-color: #1e7176; padding: 0.5rem; color: #fff; border-radius: 6px;">
                                                <input type="checkbox" name="donationCategories[]" id="category{{ $category->id }}" value="{{ $category->id }}" onclick="removeLet()" style="width: 16px; height: 16px; accent-color: #1e7176" {{ $CampaignsDonationCategoryId->contains($category->id) ? 'checked' : '' }}>
                                                <label for="category{{ $category->id }}" class="mb-0" style="font-size: 14px;">{{ __($category->name) }}</label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <div>
                                    <div class="d-flex align-items-start justify-content-start gap-2 mb-3">
                                        @foreach ($categories as $category)
                                            @if (__($category->name) == 'Let Waqfinity Decide')
                                                <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap" style="padding: 1rem 1rem 1rem 0; color: #fff; border-radius: 6px; max-width: max-content;">
                                                    <input id="wakf" type="checkbox" name="donationCategories[]" id="category{{ $category->id }}" value="{{ $category->id }}" style="width: 20px; height: 20px; accent-color: #1e7176" onchange="uncheckAll()" {{ $CampaignsDonationCategoryId->contains($category->id) ? 'checked' : '' }}>
                                                    <label for="wakf" for="category{{ $category->id }}" class="mb-0" style="color: #000; font-size: 16px; font-weight: 400;"> Let Waqfinity decide</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Image')</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text"><i class="fas fa-images"></i></span>
                                                <input type="file" name="image" id="inputAttachments" class="form-control"
                                                    accept="image/*"  @if(!$campaign->image) required @endif/>
                                            </div>
                                    @if($campaign->image)
                                        <img src="{{ getImage(getFilePath('campaign') . '/' . $campaign->image) }}" alt="" style="max-height: 200px;">
                                    @endif
                                    </div><!-- form-group end -->
                                </div>

                                <div class="document-file">
                                    <div class="document-file__input">
                                        <div class="form-group">
                                            <label>@lang('Relevent Images and Documents(.pdf)')</label>
                                            <input type="file" name="attachments[]" id="inputAttachments"
                                                class="form-control mb-2" accept=".jpg, .jpeg, .png, .pdf" required/>

                                        </div><!-- form-group end -->
                                    </div>
                                    <button type="button" class="btn cmn-btn add-new">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <div id="fileUploadsContainer"></div>
                                    <small class="text-muted mb-2">
                                        @lang('Allowed Extensions: .jpg, .jpeg, .png, .pdf')
                                    </small>

                                </div>
                            </div>
                            <button type="submit" class="btn cmn-btn w-100" type="submit">@lang('Update')</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-5 pl-md-5">
                    <div class="card custom--shadow p-3">
                        <div class="card-body">
                            <h3>@lang('Current Image') :</h3>

                            <img src="{{ getImage(getFilePath('campaign') . '/' . $campaign->image, getFileSize('campaign')) }}">

                            <h3 class="mt-4"> @lang('Current Attachements') </h3>
                            <ul class="nav nav-tabs nav-tabs--style" id="myTab" role="tablist">
                                <li class="nav-item " role="presentation">
                                    <a class="nav-link active" id="gallery-tab" data-bs-toggle="tab" href="#gallery"
                                        role="tab" aria-controls="gallery" aria-selected="false">@lang('Proof Image')</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="video-tab" data-bs-toggle="tab" href="#video" role="tab"
                                        aria-controls="video" aria-selected="false">@lang('Proof Document')</a>
                                </li>
                            </ul>
                            <div class="tab-content mt-4" id="myTabContent" >
                                <div class="tab-pane fade show active" id="gallery" role="tabpanel"
                                    aria-labelledby="gallery-tab">
                                    <div class="row gy-4">

                                        @foreach ($campaign->proof_images as $image)
                                            @if (explode('.', $image)[1] != 'pdf')
                                                <div class="col-lg-4 col-sm-6 mb-30">
                                                    <div class="gallery-card">
                                                        <a href="{{ getImage(getFilePath('proof') . '/' . $image) }}"
                                                            class="view-btn"
                                                            data-rel="lightcase:myCollection:slideshow"><i
                                                                class="las la-plus"></i></a>
                                                        <div class="gallery-card__thumb">
                                                            <img src="{{ getImage(getFilePath('proof') . '/' . $image) }}"
                                                                alt="image">
                                                        </div>
                                                    </div><!-- gallery-card end -->
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div><!-- tab-pane end -->
                                <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-tab">
                                    @foreach ($campaign->proof_images as $proof)
                                        @if (explode('.', $proof)[1] == 'pdf')
                                            <iframe class="iframe"
                                                src="{{ getImage(getFilePath('proof') . '/' . $proof) }}" frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture, pdf"
                                                allowfullscreen></iframe>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

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
        
   document.getElementById('impactConfigurationForm').addEventListener('submit', function(e) {
    
    // Create an array to store selected category IDs
    const selectedCategories = [];
   
    // Loop through all checkboxes
    document.querySelectorAll('input[name="donationCategories[]"]:checked').forEach(function(checkbox) {
      selectedCategories.push(checkbox.value);
    });
    console.log(selectedCategories);
  });
        
        
        
        
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

        //date-validation
        $(document).on('click', 'form button[type=submit]', function(e) {
            if (new Date($('.datepicker-here').val()) == "Invalid Date") {
                notify('error', 'Invalid deadline');
                return false;
            }
        });
    </script>
@endpush

@push('style')
    <style>
        .iframe {
            width: 100%;
            height: 500px;
        }
        
            .custom-checkbox {
    /* Add your custom styles here */
    width: 20px;
    height: 20px;
    accent-color: #1e7176;
    /* Add any additional styles as needed */
}
    </style>
    

@endpush
<script>
    function uncheckAll() {
        const aboveCheckboxes = document.getElementById('aboveCheckboxes');
        const checkboxes = aboveCheckboxes.querySelectorAll('input[name="donationCategories[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }
    function removeLet() {
       
        $("#wakf").prop('checked', false);
    }
     
    
</script>
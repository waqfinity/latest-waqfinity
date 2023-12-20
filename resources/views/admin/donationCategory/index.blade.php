@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        
                                        <td>
                                            <div class="user thumb">
                                                <div class="thumb w-100">
                                                    <img src="{{getImage(getFilePath('category').'/'. $category->image,getFileSize('category'))}}">
                                                    <span> {{$category->name }}</span>
                                                    
                                                </div>
                                            </div>
                                        </td>
                                        <td> @php echo $category->statusBadge @endphp </td>
                                        <td>
                                            <div class="button--group">
                                            @php $category->image_with_path = getImage(getFilePath('category').'/'.$category->image ,getFileSize('category')); @endphp
                                            <button type="button" class="btn btn-sm btn-outline--primary editBtn cuModalBtn" data-resource="{{$category}}" data-isChecked="{{ $category->is_corporate ? 'true' : 'false' }}"   data-modal_title="@lang('Edit Donation Category')" data-has_status="1">
                                                <i class="la la-pencil"></i>@lang('Edit')
                                            </button>
                                            
                                            @if($category->status == Status::DISABLE)
                                                <button type="button"
                                                        class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                                        data-action="{{ route('admin.donationCategory.status', $category->id) }}"
                                                        data-question="@lang('Are you sure to enable this donation category')?">
                                                    <i class="la la-eye"></i> @lang('Enabled')
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                data-action="{{ route('admin.donationCategory.status', $category->id) }}"
                                                data-question="@lang('Are you sure to disable this donation category')?">
                                                        <i class="la la-eye-slash"></i> @lang('Disabled')
                                                </button>
                                            @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($categories->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($categories) @endphp
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    <!--Cu Modal -->
    <div id="cuModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.donationCategory.save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Image')</label>
                            <div class="image-upload">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image: url({{ getImage(getFilePath('category'),getFileSize('category')) }})">
                                            <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="avatar-edit">
                                        <input type="file" class="profilePicUpload d-none" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg" required>
                                        <label for="profilePicUpload1" class="bg--success mt-2">@lang('Upload Image')</label>
                                        <small class="mt-1">@lang('Supported files'): <b>@lang('png'),@lang('jpg'),@lang('jpeg').</b> @lang('Image will be resized into') {{ getFileSize('category') }} </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
<x-search-form />

<button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-image_path="{{ getImage(getFilePath('category'),getFileSize('category')) }}" data-modal_title="@lang('Add New Donation Category')">
    <i class="las la-plus"></i>@lang('Add New')
</button>
@endpush


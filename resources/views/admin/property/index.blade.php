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
                                    <th>@lang('Location')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Contact person')</th>
                                    <th>@lang('Amount')</th>
                                    <th style="text-align: left;">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($properties as $property)
                                
                                    <tr>
                                        <td>
                                            <div class="user thumb">
                                                <div class="thumb w-100">
                                                    <span> {{$property->property_name }}</span>
                                                    
                                                </div>
                                            </div>
                                        </td>
                                        <td> @php echo $property->location @endphp </td>
                                        <td style="white-space: break-spaces">
                                            {{ $property->description }}

                                        </td>             
                                        <td>
                                            {{ $property->key_contact }}

                                        </td>                                       
                                         <td>
                                            {{ $property->amount }}

                                        </td>                         
                                        <td >
                                        <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                            data-action="{{ route('admin.property.delete', $property->id) }}"
                                            data-question="@lang('Are you sure to delete the property')?">
                                                    <i class="la la-trash"></i> @lang('Delete')
                                            </button>

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
                @if ($properties->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($properties) @endphp
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
                <form action="{{ route('admin.property.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" name="property_name" class="form-control" required>
                        </div>
                         
                    <div class="form-group">
                        <label>@lang('Location')</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>                   
                    <div class="form-group">
                        <label>@lang('Amount')</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>                    
                    <div class="form-group">
                        <label>@lang('Key person to contact')</label>
                        <input type="text" name="key_contact" class="form-control" required>
                    </div>
                     <div class="form-group">
                        <label>@lang('Description')</label>
                        <textarea name="description" class="form-control" rows="5" cols="3"></textarea>
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
<button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-image_path="{{ getImage(getFilePath('category'),getFileSize('category')) }}" data-modal_title="@lang('Add New Property')">
    <i class="las la-plus"></i>@lang('Add New')
</button>
@endpush



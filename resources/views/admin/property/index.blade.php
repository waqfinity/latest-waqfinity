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
                                    <th>@lang('Contact person name')</th>
                                    <th>@lang('Contact person mobile')</th>
                                    <th>@lang('Contact person email')</th>
                                    <th>@lang('Documentation URL')</th>
                                    <th>@lang('Value')</th>
                                    <th style="text-align: left;">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($properties as $property)
                                
                                    <tr>
                                        <td>
                                          {{$property->property_name }}
                                        </td>
                                        <td> @php echo $property->location @endphp </td>
                                        <td>
                                            {{ $property->description }}

                                        </td>             
                                        <td>
                                            {{ $property->key_person_name }}

                                        </td>                                      
                                        <td>
                                            {{ $property->key_person_mobile }}

                                        </td>                                           <td>
                                            {{ $property->key_person_email }}

                                        </td>                                        
                                        <td>
                                            <a href="{{ $property->property_doc_url }}" target="_blank"> View Document </a>

                                        </td>                                       
                                         <td>
                                            £{{ $property->amount }}

                                        </td>                         
                                        <td >
                                        <button type="button" class="btn btn-sm btn-outline--primary editBtn cuModalBtn" data-resource="{{$property}}"   data-modal_title="@lang('Edit Property')" data-has_status="1">
                                            <i class="la la-pencil"></i>@lang('Edit')
                                        </button>
                                         @if ($property->investments->isNotEmpty())
                                            <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                            disabled>
                                                    <i class="la la-trash"></i> @lang('Delete')
                                            </button>
                                            @else
                                             <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                            data-action="{{ route('admin.property.delete', $property->id) }}"
                                            data-question="@lang('Are you sure to delete the property')?">
                                                    <i class="la la-trash"></i> @lang('Delete')
                                            </button>
                                            @endif

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
                        <label>@lang('Value')(£)</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>                    
                    <div class="form-group">
                        <label>@lang('Name of the person to contact')</label>
                        <input type="text" name="key_person_name" class="form-control" required>
                    </div>                     
                    <div class="form-group">
                        <label>@lang('Contact person mobile')</label>
                        <input type="text" name="key_person_mobile" class="form-control" required>
                    </div>                     
                    <div class="form-group">
                        <label>@lang('Contact person Email')</label>
                        <input type="text" name="key_person_email" class="form-control" required>
                    </div>                   
                     <div class="form-group">
                        <label>@lang('Documentation URL')</label>
                        <input type="text" name="property_doc_url" class="form-control" required>
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



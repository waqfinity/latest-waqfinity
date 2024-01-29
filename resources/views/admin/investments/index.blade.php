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
                                    <th>@lang('Property')</th>
                                    <th>@lang('Patch')</th>
                                    <th style="text-align: left;">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($investments as $investment)
                                
                                    <tr>
                                        <td>
                                            <div class="user thumb">
                                                <div class="thumb w-100">
                                                    <span> {{$investment->name }}</span>
                                                    
                                                </div>
                                            </div>
                                        </td>
                                            
                                        <td>
                                            {{ $investment->property->property_name }}

                                        </td>                                       
                                         <td>
                                            {{ $investment->patch->name }}

                                        </td>                         
                                        <td >
                                        <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                            data-action="{{ route('admin.investment.delete', $investment->id) }}"
                                            data-question="@lang('Are you sure to delete the Investment')?">
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
                @if ($investments->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($investments) @endphp
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
                <form action="{{ route('admin.investment.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                          @php
                            $properties = \App\Models\Property::select('id', 'property_name')->get();                           
                            @endphp
                        <div class="form-group">
                            <label>@lang('Property')</label>
                            <select name="property_id" id="property_id"  class="form-control" required>
                                <option selected hidden>Select a property</option>
                                @foreach($properties as $property)
                                       <option value="{{ $property['id'] }}">{{ $property['property_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Amount / Patch')</label>
                            @php
                            $patches = \DB::table('patches')->select('id', 'name', 'amount')->get();
                            @endphp
                            <select name="patch_id" class="form-control">
                                @foreach($patches as $patch)
                                    <option selected hidden>Select amount</option>
                                    <option value="{{ $patch->id }}">{{ $patch->amount }} ( {{ $patch->name }} )</option>
                                @endforeach
                            </select>
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
<button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-image_path="{{ getImage(getFilePath('category'),getFileSize('category')) }}" data-modal_title="@lang('Add New Investment')">
    <i class="las la-plus"></i>@lang('Add New')
</button>
@endpush



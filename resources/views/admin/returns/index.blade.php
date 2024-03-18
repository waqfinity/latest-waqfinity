@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm">
                        <table class="table table--light">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('From')</th>
                                    <th>@lang('To')</th>
                                    <th>@lang('Value')</th>
                                    <th style="text-align: left;">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($returns as $return)
                                
                                    <tr>
                                        <td>
                                         {{ $return->property->property_name}}
                                        </td>
                                        <td>
                                              {{ \Carbon\Carbon::parse($return->from)->format('M d, Y') }}
                                        </td>
                                        <td>
                                           
                                            {{ \Carbon\Carbon::parse($return->to)->format('M d, Y') }}

                                        </td>             
                                                                            
                                         <td>
                                            £{{ $return->amount }}

                                        </td>                         
                                        <td style="text-align:left !important;">
                                                <button type="button" class="btn btn-sm btn-outline--primary editBtn cuModalBtn" data-resource="{{ $return }}"   data-modal_title="@lang('Edit Return')" data-has_status="1">
                                                <i class="la la-pencil"></i>@lang('Edit')
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
                @if ($returns->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($returns) @endphp
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
                <form action="{{ route('admin.returns.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                             @php
                                 $properties = \App\Models\Property::select('id', 'property_name', 'amount')->get();                           
                            @endphp

                        <div class="form-group">
                            <label>@lang('Property')</label>
                            <select name="property_id" id="property_id"  class="form-control" required>
                                <option selected hidden>Select a property</option>
                                @foreach($properties as $property)
                                       <option value="{{ $property['id'] }}">{{ $property['property_name'] }} (£{{ $property['amount']}})</option>
                                @endforeach
                            </select>
                        </div>
                         
                    <div class="form-group">
                        <label>@lang('From')</label>
                        <input type="date" name="from" class="form-control" required>
                    </div>                   
                       
                    <div class="form-group">
                        <label>@lang('To')</label>
                        <input type="date" name="to" class="form-control" required>
                    </div>            
                    <div class="form-group">
                        <label>@lang('Amount')(£)</label>
                        <input type="number" name="amount" class="form-control" required>
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
<button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-image_path="{{ getImage(getFilePath('category'),getFileSize('category')) }}" data-modal_title="@lang('Add Returns')">
    <i class="las la-plus"></i>@lang('Add New')
</button>
@endpush
@push('style')

@endpush



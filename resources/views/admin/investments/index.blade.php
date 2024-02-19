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
                                    <th>@lang('Total Amount')</th>
                                    <th>@lang('Document Url')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Status')</th>
                                    <th >@lang('Action')</th>
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
                                            @php
                                                $patchIds = json_decode($investment->patch_id, true);
                                                $patchNames = [];
                                                // Assuming Patch is the model for your patch
                                                foreach ($patchIds as $patchId) {
                                                    $patch = \App\Models\Patch::find($patchId);
                                                    if ($patch) {
                                                        $patchNames[] = $patch->name;
                                                    }
                                                }
                                            @endphp

                                            @if(count($patchNames) > 0)
                                                <ul>
                                                    @foreach($patchNames as $patchName)
                                                        <li>{{ $patchName }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                No patch names available.
                                            @endif
                                        </td>

                                        <td> £{{ $investment->total_amount }} </td>

                                        <td> <a href="{{ $investment->doc_url }}" target="_blank">View documennt</a></td>

                                        <td>{{ \Carbon\Carbon::parse($investment->start_date)->format('M d, Y') }}</td>


                                        <td class="{{$investment->status}}">{{ $investment->status}}</td>
                       
                                        <td >
                                            <button type="button" class="btn btn-sm btn-outline--primary editBtn cuModalBtn" data-resource="{{$investment}}"   data-modal_title="@lang('Edit Investment')" data-has_status="1">
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
                            <input type="text" name="name" placeholder="Enter investemnet name" class="form-control" required>
                        </div>
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
                            <label class="required">@lang('Patches')</label>
                            @php
                            $patches = \DB::table('patches')->select('id', 'name', 'amount')->get();
                            @endphp
                                @foreach($patches as $patch)

                                <div class="form-check ps-0 d-flex align-items-center gap-2">
                                        <input type="checkbox" name="patch_id[]" value="{{ $patch->id }}" id="{{ $patch->amount }}">
                                        <label for="patch" class="mb-0"> {{ $patch->name }} ( Value : £{{ $patch->amount}})</label>
                                </div>
                                @endforeach
                        </div>   
                        <div class="form-group">
                            <label>@lang('Total amount'):</label>
                            <input type="text" name="total_amount" id="total_amount" class="form-control" value="0" readonly>
                        </div>  

                        <div id="selectedCheckboxDiv">
                            <span id="ValueAsset"></span>
                        </div>                       

                        <div class="form-group">
                            <label>@lang('Start Date'):</label>
                            <input name="start_date" type="date" data-language="en" class="datepicker-here form-control" data-position='bottom left' autocomplete="off" required>
                        </div>  

                        <div class="form-group">
                            <label>@lang('Documents Url'):</label>
                            <input type="text" name="doc_url" id="total_amount" class="form-control" placeholder="Enter url " required>
                        </div>                          
                        <div class="form-group">
                            <label>@lang('Status'):</label>

                             <select name="status" class="form-control" required>
                                    <option value="open">Open</option>
                                    <option value="closed">Closed</option>
                                    <option value="pending">Pending</option>
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

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var checkboxes = document.querySelectorAll('input[name="patch_id[]"]');
        var selectedCheckboxDiv = document.getElementById('selectedCheckboxDiv');
        var sumOfSelectedCheckboxIdsSpan = document.getElementById('sumOfSelectedCheckboxIds');
        var totalAmountInput = document.getElementById('total_amount');

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                var checkedCheckboxes = document.querySelectorAll('input[name="patch_id[]"]:checked');
                var sumOfIds = 0;

                checkedCheckboxes.forEach(function (checkedCheckbox) {
                    var idValue = parseFloat(checkedCheckbox.id.replace(',', ''));
                    sumOfIds += idValue;
                });

                if (!isNaN(sumOfIds) && sumOfIds > 0) {
                    console.log(sumOfIds);
                     totalAmountInput.value = sumOfIds.toFixed(2);
                     sumOfSelectedCheckboxIdsSpan.style.display = 'block';
                      selectedCheckboxDiv.style.display = 'block';
                  
                } else {
                    totalAmountInput.value = '0';
                    sumOfSelectedCheckboxIdsSpan.style.display = 'none';
                    selectedCheckboxDiv.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
@push('style') 
<style>
    tr .open{
        color: green !important;
    }    
    tr .closed{
        color: red !important;
    }    
    tr .pending{
        color: blue !important;
    }
</style>
@endpush





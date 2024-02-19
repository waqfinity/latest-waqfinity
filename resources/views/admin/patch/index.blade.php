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
                                    <th>@lang('Months')</th>
                                    <th>@lang('Value')</th>
                                    <th>@lang('Total no of donations')</th>
                                    <th>@lang('Status')</th>
                                    <th style="text-align: left;">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patches as $patch)
                                
                                    <tr>
                                        <td>
                                            <div class="user thumb">
                                                <div class="thumb w-100">
                                                    <span> {{$patch->name }}</span>
                                                    
                                                </div>
                                            </div>
                                        </td>           
                                        <td>
                                         @foreach (json_decode(stripslashes($patch->donation_ids), true) as $date)
                                         @php
                                                $monthYear = \Carbon\Carbon::parse(\DateTime::createFromFormat('m-Y', $date))->format('Y-m');
                                                $donationCount = \App\Models\Donation::whereYear('created_at', '=', $monthYear)
                                                    ->whereMonth('created_at', '=', \Carbon\Carbon::parse($monthYear)->month)
                                                    ->count();
                                            @endphp
                                            {{ \Carbon\Carbon::parse(\DateTime::createFromFormat('m-Y', $date))->format('M-Y') }},
                                        @endforeach
                                       </td>                                       
                                         <td>
                                            £{{ $patch->amount }}

                                        </td>   
                                        <td>
                                            {{ $donationCount }}
                                       </td>   
                                       <td>
                                            @if($patch->investments->isEmpty())
                                                <span class="text-warning">@lang('Unused')</span>
                                            @else
                                                <span class="text-success">@lang('Invested in property')</span>
                                                 <ul>
                                                    @foreach($patch->investments as $investment)
                                                        @php
                                                            $propertyLink = route('admin.property.index', ['search' => $investment->property->property_name]);
                                                        @endphp
                                                        <li><a href="{{ $propertyLink }}">Property Name: {{ $investment->property->property_name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                       </td>                    
                                        <td class="text-start">
                                        @if($patch->investments->isEmpty())
                                            <button type="button" class="btn btn-sm btn-outline--primary editBtn cuModalBtn" data-resource="{{$patch}}"   data-modal_title="@lang('Edit Patch')" data-has_status="1">
                                                <i class="la la-pencil"></i>@lang('Edit')
                                            </button>
                                        @else
                                          <button type="button" class="btn btn-sm btn-outline--primary editBtn" data-bs-toggle="tooltip" data-bs-placement="right" title="Edit only if status unused" style="cursor: no-drop;"> <i class="la la-pencil"></i> @lang('Edit')</button>
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
                @if ($patches->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($patches) @endphp
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
                <form action="{{ route('admin.patch.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name'):</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>@lang('Donations per month'):</label>
                            <small class="text-warning d-block mb-2" style="font-size:11px">( Below are the donations calculated per month. Select them to create one patch. )</small>
                            @php
                            $donationIds = \App\Models\Patch::pluck('donation_ids')->toArray();
                            $donationsByMonth = \App\Models\Donation::select(
                                DB::raw('MONTH(created_at) as month'),
                                DB::raw('YEAR(created_at) as year'),
                                DB::raw('FORMAT(SUM(donation), 2) as total_amount'),
                                DB::raw('COUNT(*) as donation_count')
                            )
                                ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'))->get();
                            @endphp
                            <div class="d-flex flex-column align-items-start gap-2">
                                
                                @foreach($donationsByMonth as $monthlyTotal)

                                
                                    @php
                                        $monthYear = $monthlyTotal->month . '-' . $monthlyTotal->year;
                                        $monthYear2 = \Carbon\Carbon::createFromDate($monthlyTotal->year, $monthlyTotal->month, 1)->format('F, Y');
                                        $donationCount = $monthlyTotal->donation;
                                        $hideMonth = false;
                                        foreach ($donationIds as $donationId) {
                                            $donationIdsArray = json_decode($donationId, true);
                                            if (in_array($monthYear, $donationIdsArray)) {
                                                $hideMonth = true;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @unless($hideMonth)
                                    <div class="form-check ps-0 d-flex align-items-center gap-2">
                                        <input type="checkbox" name="selected_months[]" value="{{ $monthYear }}" id="{{ $monthlyTotal->total_amount }}">
                                        <label class="form-check-label mb-0" for="{{ $monthYear }}">
                                         <b>Month</b> : {{ $monthYear2 }} <br> <b>No of donations</b> : {{ $monthlyTotal->donation_count}} <br> <b>Total value </b>: £{{ $monthlyTotal->total_amount }} 
                                        </label> 
                                    </div>
                                    @endunless
                                @endforeach
                            </div>
                        </div>
                          <div class="form-group">
                            <label>@lang('Total amount'):</label>
                            <input type="text" name="amount" id="total_amount" class="form-control" value="0" readonly>
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
<button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-image_path="{{ getImage(getFilePath('category'),getFileSize('category')) }}" data-modal_title="@lang('Add New Patch')">
    <i class="las la-plus"></i>@lang('Add New')
</button>
@endpush
@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var checkboxes = document.querySelectorAll('input[name="selected_months[]"]');
        var selectedCheckboxDiv = document.getElementById('selectedCheckboxDiv');
        var sumOfSelectedCheckboxIdsSpan = document.getElementById('sumOfSelectedCheckboxIds');
        var totalAmountInput = document.getElementById('total_amount');

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                var checkedCheckboxes = document.querySelectorAll('input[name="selected_months[]"]:checked');
                var sumOfIds = 0;

                checkedCheckboxes.forEach(function (checkedCheckbox) {
                    var idValue = parseFloat(checkedCheckbox.id.replace(',', ''));
                    sumOfIds += idValue;
                });

                if (!isNaN(sumOfIds) && sumOfIds > 0) {
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
 


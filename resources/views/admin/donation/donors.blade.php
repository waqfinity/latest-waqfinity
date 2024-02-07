@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class=" table align-items-center table--light">
                            <thead>
                                <tr>
                                    <th>@lang('Full Name')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Mobile')</th>
                                    <th>@lang('City')</th> 
                                    <th>@lang('Total No Of Donations')</th> 
                                    <th>@lang('Total Amount')</th> 
                                    <th>@lang('Address')</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->fullname }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->city }}</td>  
                                        <td>{{ $user->total_donations }}</td> 
                                        <td>£{{ number_format($user->total_amount, 2) }}
                                        </td>
                                        <td>{{ $user->address }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-muted text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
               
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <x-search-form/>
   
@endpush


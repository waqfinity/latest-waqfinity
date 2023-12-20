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
                                    <th>@lang('full name')</th>
                                    <th>@lang('email')</th>
                                    <th>@lang('mobile')</th>
                                    <th>@lang('city')</th> 
                                    <th>@lang('address')</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->fullname }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->city }}</td>  
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


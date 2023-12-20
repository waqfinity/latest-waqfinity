@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Phone')</th>
                                    <th>@lang('Country')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Joined At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($volunteers as $volunteer)
                                    <tr>
                                        <td>
                                            <div class="user thumb">
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('volunteer') . '/' . $volunteer->image, getFileSize('volunteer')) }}">
                                                    <span class="name" >{{ $volunteer->fullname }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $volunteer->email }}
                                        </td>
                                        <td>
                                            {{ $volunteer->mobile }}
                                        </td>
                                        <td >
                                            {{ @$volunteer->address->country ?? "N/A"  }}
                                        </td>
                                        <td>
                                            @php  echo $volunteer->statusBadge; @endphp
                                        </td>
                                        <td>
                                            {{ showDateTime($volunteer->created_at) }}
                                            <span class="d-block">{{ diffForHumans($volunteer->created_at) }}</span>
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.volunteer.details', $volunteer->id) }}"
                                                    class="btn btn-sm btn-outline--primary ms-1"><i
                                                        class="las la-desktop"></i>
                                                    @lang('Details')</a>
                                                @if ($volunteer->status == Status::VOLUNTEER_ACTIVE)
                                                    <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sure to inactive the volunteer')?"
                                                        data-action="{{ route('admin.volunteer.status', $volunteer->id) }}">
                                                        <i class="la la-eye-slash"></i> @lang('Inactive')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sure to active the volunteer')?"
                                                        data-action="{{ route('admin.volunteer.status', $volunteer->id) }}">
                                                        <i class="la la-eye"></i> @lang('Active')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center text-muted">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($volunteers->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($volunteers) @endphp
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form />
@endpush

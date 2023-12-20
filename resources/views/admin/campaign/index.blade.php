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
                                    <th>@lang('Campaign')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Goal')</th>
                                    <th>@lang('Deadline')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($campaigns as $campaign)
                                    <tr>
                                        <td>
                                            <div class="user thumb">
                                                <div class="thumb w-100">
                                                    <img src="{{ getImage(getFilePath('campaign') . '/' . $campaign->image, getFileSize('campaign')) }}">
                                                    <span> {{ strLimit($campaign->title, 25) }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ @$campaign->category->name }}
                                        </td>
                                        <td>
                                            <span class="d-block"> {{ @$campaign->user->fullname }}</span>
                                            <a class="text--small" href="{{ appendQuery('search', @$campaign->user->username) }}"><span>@</span>{{ @$campaign->user->username }}</a>
                                        </td>
                                        <td> {{ $general->cur_sym }}{{ showAmount($campaign->goal) }} </td>
                                        <td>
                                            {{ showDateTime($campaign->deadline, 'd-m-Y') }}
                                            <span class="text--small d-block">{{ diffForHumans($campaign->deadline) }}</span>
                                        </td>
                                        <td> @php echo $campaign->statusBadge;@endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.fundrise.details',$campaign->id) }}"
                                                    class="btn btn-sm btn-outline--primary ms-1 mb-2">
                                                    <i class="las la-desktop"></i>@lang('Details')
                                                </a>
                                                @if (request()->routeIs('admin.fundrise.rejected'))
                                                    <button type="button" class="btn btn-sm btn-outline--danger ms-1 mb-2 confirmationBtn" data-action="{{ route('admin.fundrise.delete', $campaign->id) }}" data-question="@lang('Are you sure to delete this campaign?')">
                                                        <i class="la la-trash"></i>@lang('Delete')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
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
                @if ($campaigns->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($campaigns) @endphp
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

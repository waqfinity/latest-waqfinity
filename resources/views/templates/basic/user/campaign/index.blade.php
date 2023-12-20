@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-end gy-4">
                <div class="col-lg-4 col-sm-12">
                    <form action="">
                        <div class="input-group">
                            <input type="search" name="search" class="form-control" value="{{ request()->search }}"
                                placeholder="@lang('Search by title')">
                            <button class="input-group-text bg-cmn text-white border-0">
                                <i class="las la-search"></i>
                            </button>
                        </div>

                    </form>
                </div>
                <div class="col-12">
                    <table class="table table--responsive--md">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Goal')</th>
                                <th>@lang('Fund Raised')</th>
                                <th>@lang('Deadline') | @lang('Created')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($campaigns as  $item)
                                <tr>
                                    <td>{{ $campaigns->firstItem() + $loop->index }}</td>
                                    <td>
                                        <a href="{{ route('user.campaign.fundrise.view', ['slug' => $item->slug, 'id' => $item->id]) }}"
                                            title="@lang('Details')">
                                            {{ strLimit($item->title, 20) }}
                                        </a>
                                    </td>
                                    <td>{{ $general->cur_sym }}{{ showAmount($item->goal) }} </td>
                                    <td>{{ $general->cur_sym }}{{ showAmount($item->donation->where('status', Status::DONATION_PAID)->sum('donation')) }}
                                    </td>
                                    <td>
                                        <div>
                                        {{ showDateTime($item->deadline, 'd-m-Y') }}
                                        <span class="d-block">{{ diffForHumans($item->created_at) }}</span>
                                    </div>
                                    </td>
                                    <td>
                                        @php
                                            $hasDonations = $item->donation->where('status', Status::DONATION_PAID)->count();
                                        @endphp

                                       <div>
                                        @if (request()->routeIs('user.campaign.fundrise.pending'))
                                        @if ($item->expired())
                                            <a href="{{ route('user.campaign.fundrise.edit', $item->id) }}">
                                                <i title="Edit" class="la la-edit bg-primary text-white p-2 rounded"></i></a>
                                        @endif
                                    @endif
                                    @if(request()->routeIs('user.campaign.fundrise.rejected'))
                                    <a href="javascript:void(0)" class="confirmationBtn"
                                    data-question="@lang('Are you sure to delete the expired campaign')?"
                                                    data-action="{{ route('user.campaign.fundrise.delete', $item->id) }}"><i title="@lang('Trash request')?" class="la la-trash bg-danger text-white p-2 rounded"></i></a>
                                    @endif

                                    @if (request()->routeIs('user.campaign.fundrise.pending') ||
                                            request()->routeIs('user.campaign.fundrise.rejected') ||
                                            request()->routeIs('user.campaign.fundrise.complete'))
                                        <a href="{{ route('user.campaign.fundrise.view', [$item->slug, $item->id]) }}"
                                            title="@lang('Details')">
                                            <i class="bg-cmn text-white p-2 rounded la la-desktop"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('user.campaign.fundrise.view', [$item->slug, $item->id]) }}"
                                            title="@lang('Details')">
                                            <i class="bg-cmn text-white p-2 rounded la la-desktop"></i>
                                        </a>
                                        <a href=" {{ route('user.campaign.donation.received', $item->id) }}">
                                            @if (@$hasDonations)
                                                <i title="@lang('Donor List')"
                                                    class="la la-user bg-info  text-white p-2 rounded"></i>
                                            @else
                                                <i class="la la-user bg-secondary text-white p-2 rounded"></i>
                                            @endif
                                        </a>

                                        @if ($item->completed == Status::NO)
                                            <a data-question="@lang('Are you sure to campaign complete action? Because this action can\'t back again!')"
                                                data-action="{{ route('user.campaign.fundrise.make.complete', $item->id) }}"
                                                class="confirmationBtn">
                                                <i title="@lang('Complete')?"
                                                    class="la la-check bg-warning text-white p-2 rounded"></i>
                                            </a>
                                        @endif
                                        @if (!request()->routeIs('user.campaign.fundrise.expired'))
                                            @if ($item->stop)
                                                <a data-question="@lang('Are you sure to run this campaign?')"
                                                    data-action="{{ route('user.campaign.fundrise.stop', $item->id) }}"
                                                    class="confirmationBtn" data-title="@lang('Campaign Run')">
                                                    <i title="@lang('Run')?"
                                                        class="la la-pause-circle bg-primary text-white p-2 rounded"></i>
                                                </a>
                                            @else
                                                <a data-question="@lang('Are you sure to stop this campaign?')"
                                                    data-action="{{ route('user.campaign.fundrise.stop', $item->id) }}"
                                                    class="confirmationBtn mt-1" data-title="@lang('Campaign Run')">
                                                    <i title="@lang('Stop')?"
                                                        class="la la-pause-circle bg-danger text-white p-2 rounded"></i>
                                                </a>
                                            @endif
                                        @endif
                                    @endif
                                       </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">
                                        {{ __($emptyMessage) }} <i class="la la-laugh"></i>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
                @if ($campaigns->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $campaigns->links() }}
                    </div>
                @endif
            </div>
        </div>
        <x-confirmation-modal />
    </div>
@endsection


@push('style')
<style>
    .btn-sm-radious{
        border-radius: 5px !important

    }

</style>
@endpush

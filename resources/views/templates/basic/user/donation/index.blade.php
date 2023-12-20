@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center my-5">
            <div class="table-responsive--md table-responsive">
<table class="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('S.N.')</th>
                            <th>@lang('trx').</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Mobile')</th>
                            <th>@lang('Country')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    @forelse ($donations as $item)
                        <tr>
                            <td>{{ $donations->firstItem() + $loop->index }}</td>
                            <td>{{ @$item->deposit->trx }}</td>
                            <td>{{ __($item->fullname) }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->mobile }}</td>
                            <td>{{ $item->country }}</td>
                            <td>{{ $general->cur_sym }}{{ showAmount($item->donation) }} </td>
                            <td>
                                <a href="{{ route('user.campaign.donation.details', $item->id) }}"><i class="la la-desktop bg-cmn text-white p-2 rounded"
                                        title="Show Details"></i></a>
                            </td>

                        </tr>
                    @empty
                    <tr>
                        <td colspan="100%" class="text-center">
                            {{ __($emptyMessage) }} <i class="la la-laugh"></i>
                        </td>
                    </tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
@endsection


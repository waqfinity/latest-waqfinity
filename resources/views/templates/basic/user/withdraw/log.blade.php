@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-end gy-4">
                <div class="col-lg-4 col-sm-12">
                    <form action="">
                        <div class="input-group">
                            <input type="search" name="search" class="form-control" value="{{ request()->search }}"
                                placeholder="@lang('Search by transactions')">
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
                                <th>@lang('Gateway | Transaction')</th>
                                <th>@lang('Initiated')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Conversion')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($withdraws as $withdraw)
                                <tr>
                                    <td>
                                       <div>
                                        <span class="fw-bold"><span class="text-primary">
                                            {{ __(@$withdraw->method->name) }}</span></span>
                                    <br>
                                    <small>{{ $withdraw->trx }}</small>
                                       </div>
                                    </td>
                                    <td class="text-center">
                                     <div>
                                        {{ showDateTime($withdraw->created_at) }} <br>
                                        {{ diffForHumans($withdraw->created_at) }}
                                     </div>
                                    </td>
                                    <td class="text-center">
                                        <div>
                                            {{ __($general->cur_sym) }}{{ showAmount($withdraw->amount) }} - <span
                                            class="text-danger"
                                            title="@lang('charge')">{{ showAmount($withdraw->charge) }} </span>
                                        <br>
                                        <strong title="@lang('Amount after charge')">
                                            {{ showAmount($withdraw->amount - $withdraw->charge) }}
                                            {{ __($general->cur_text) }}
                                        </strong>

                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div>
                                            1 {{ __($general->cur_text) }} = {{ showAmount($withdraw->rate) }}
                                        {{ __($withdraw->currency) }}
                                        <br>
                                        <strong>{{ showAmount($withdraw->final_amount) }}
                                            {{ __($withdraw->currency) }}</strong>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php echo $withdraw->statusBadge @endphp
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn--base detailBtn"
                                            data-user_data="{{ json_encode($withdraw->withdraw_information) }}"
                                            @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                            <i class="bg-cmn text-white p-2 rounded la la-desktop"></i>
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
                    @if ($withdraws->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $withdraws->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Details MODAL --}}
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData">

                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .input-group {
            border: 0;
        }

        .form-control {
            border: 1px solid #e5e5e5 !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    }
                });
                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);

                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

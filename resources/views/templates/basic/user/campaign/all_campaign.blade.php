@extends($activeTemplate . 'layouts.master')

@section('content')
    <section class="pt-120 pb-120">
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
                    <table class="table table--responsive--lg">
                        <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th>@lang('Goal')</th>
                                <th>@lang('Fund Raised')</th>
                                <th>@lang('Deadline')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($campaigns as $item)
                                @php
                                    $donation = $item->donation->where('status', Status::DONATION_PAID);
                                    $hasDonations = $donation->count();
                                @endphp
                                <tr>
                                    <td>{{ strLimit($item->title, 30) }}</td>
                                    <td>{{ $general->cur_sym }}{{ showAmount($item->goal) }} </td>
                                    <td>
                                        {{ $general->cur_sym }}{{ showAmount($donation->sum('donation')) }}
                                    </td>
                                    <td> {{ showDateTime($item->deadline, 'd-m-Y') }}</td>
                                    <td>
                                        @php echo $item->statusBadge; @endphp
                                    </td>
                                    <td>
                                       <div>
                                        @if ($item->deadline < now())
                                        <a href="javascript:void(0)" data-title="{{ $item->title }}" data-goal="{{ $item->goal }}"
                                            data-action="{{ route('user.campaign.fundrise.extended', $item->id) }}"
                                            class="extendBtn">
                                            <i title="@lang('Extend request')?"
                                                class="la la-radiation-alt bg-primary text-white p-2 rounded"></i>
                                        </a>
                                    @endif
                                    <a
                                        href="{{ route('user.campaign.fundrise.view', ['slug' => $item->slug, 'id' => $item->id]) }}"><i
                                            class="bg-cmn text-white p-2 rounded la la-desktop"></i></a>

                                    @if (@$hasDonations)
                                        <a href="{{ route('user.campaign.donation.my', $item->id) }}"><i
                                                title="@lang('Donors List')"
                                                class="la la-user bg-info  text-white p-2 rounded"></i></a>
                                    @else
                                        <i class="la la-user bg-secondary text-white p-2 rounded"></i>
                                    @endif
                                       </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%">
                                        <p class="text-center"> {{ __($emptyMessage) }} <i class="fa fa-laugh"></i></p>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                @if ($campaigns->hasPages())
                    @php echo paginateLinks($campaigns) @endphp
                @endif
            </div>
        </div>
        {{-- //Extend The Expired Campaign modal --}}
        <div class="modal fade" tabindex="-1" role="dialog" id="extendedModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Are you sure to extend the campaign')?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body">
                            <h4 class="campaign-title"></h4>
                            @csrf
                            <div class="form-group">
                                <label>@lang('Extend Deadline')</label>
                                <input name="deadline" type="text" data-language="en"
                                    class="datepicker-here form-control bg--white" autocomplete="off"
                                    value="{{ date('Y-m-d') }}" data-date-format="yyyy-mm-dd" required>
                                <small class="text-muted text--small"> <i class="la la-info-circle"></i>
                                    @lang('Year-Month-Date')</small>
                            </div>

                            <div class="form-group">
                                <label>@lang('Extend Goal') </label>
                                <div class="input-group">
                                    <input type="number" step="any" required name="goal"
                                        value="{{ old('goal') }}" class="form-control">
                                    <span class="input-group-text">{{ __($general->cur_text) }} </span>
                                </div>
                                <code class="was-goal"></code>
                            </div>
                            <div class="form-group">
                                <label>@lang('Final Goal')</label>
                                <div class="input-group">
                                    <input type="number" step="any" required name="final_goal"
                                        value="{{ old('final_goal') }}" class="form-control" readonly>
                                    <span class="input-group-text">{{ __($general->cur_text) }} </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="cmn-btn btn-sm">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/datepicker.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';

        $(function() {

            $('.extendBtn').on('click', function(e) {
                e.preventDefault();
                let route = $(this).data('action');
                let title = $(this).data('title');
                let goal = parseFloat($(this).data('goal'));
                let curText = `{{ $general->cur_text}}`;
                var modal = $('#extendedModal');
                modal.find('.modal-body .campaign-title').text(`${title}`);
                modal.find('.modal-body .was-goal').text(`@lang('Previous Goal'):` +`${goal}` + ' '+ `${curText}`);
                modal.find('form').attr('action', route);

                $(document).on('input', '[name=goal]' , function(){
                    const currentGoal = parseFloat($(this).val());
                    var finalGoal   = goal + currentGoal;
                    $('[name=final_goal]').val(finalGoal);
                })

                modal.modal('show');
            });

            //date-validation
            $(document).on('click', 'form button[type=submit]', function(e) {
                if (new Date($('.datepicker-here').val()) == "Invalid Date") {
                    notify('error', 'Invalid extend deadline');
                    return false;
                }
            });
        })
    </script>
@endpush

@push('style')
    <style>
        .datepickers-container {
            z-index: 9999999999;
        }
    </style>
@endpush

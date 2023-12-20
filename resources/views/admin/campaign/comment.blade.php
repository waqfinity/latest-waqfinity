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
                                    <th>@lang('Name') | @lang('Email')</th>
                                    <th>@lang('Review')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Created At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $comment)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.fundrise.details', @$comment->campaign_id) }}">{{ __(strLimit(@$comment->campaign->title, 20)) }}</a>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ __($comment->fullname) }}</span>
                                            <span class="d-block">{{ $comment->email }}</span>
                                        </td>
                                        <td>{{ strLimit($comment->comment, 30) }}</td>
                                        <td>
                                            @php
                                                echo $comment->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            {{ showDateTime($comment->created_at) }}
                                            <span class="d-block">{{ diffForHumans($comment->created_at) }}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary detailBtn"
                                                    data-resourse="{{ $comment }}">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </button>
                                            @if ($comment->status == Status::PENDING)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success  confirmationBtn"
                                                    data-action="{{ route('admin.fundrise.comment.status', $comment->id) }}"
                                                    data-question="@lang('Are you sure to publish this comment?')">
                                                    <i class="la la-eye"></i> @lang('Publish')
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.fundrise.comment.status', $comment->id) }}"
                                                    data-question="@lang('Are you sure to pending this comment?')">
                                                    <i class="la la-eye-slash"></i> @lang('Pending')
                                                </button>
                                            @endif
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

                @if ($comments->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($comments) @endphp
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    {{-- DETAILS MODAL --}}
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Camapign Comment')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group-flush list-group">
                        <li class="list-group-item align-items-center fw-bold">
                            <p class="comment text-end"></p>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection
@push('breadcrumb-plugins')
    <x-search-form />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal    = $('#detailModal');
                var resourse = $(this).data('resourse');
                $('.comment').text(resourse.comment);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

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
                                    <th>@lang('Story')</th>
                                    <th>@lang('Commenter')</th>
                                    <th>@lang('E-Mail')</th>
                                    <th>@lang('Comments')</th>
                                    <th>@lang('Date of Comment')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($storyComments as $comment)
                                    <tr>
                                        <td>
                                            <div class="user thumb">
                                                <div class="thumb w-100">
                                                    <img src="{{ getImage(getFilePath('success') . '/' . @$comment->story->image, getFileSize('success')) }}">
                                                    <span><a href="{{ route('admin.success.story.detail', $comment->story->id) }}"> {{ strLimit($comment->story->title, 20) }}</a></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ __($comment->commenter) }}</td>
                                        <td>{{ $comment->email }}</td>
                                        <td>
                                            {{ __(strLimit($comment->comment, 30)) }}
                                            @if (strlen($comment->comment) > 30)
                                                <br>
                                                <span class="btn text--small text--primary commentDtlBtn"
                                                    data-comment="{{ $comment->comment }}" data-title="{{ $comment->story->title }}">@lang('Read More')</span>
                                            @endif
                                        </td>
                                        <td>{{ diffForHumans(@$comment->created_at) }}</td>
                                        <td>
                                            @php echo $comment->statusBadge; @endphp
                                        </td>
                                        <td>
                                            @if ($comment->status == 0)
                                                <button class="btn btn-outline--success btn-sm ms-1 confirmationBtn approve"
                                                data-action="{{route('admin.success.story.comment.status', $comment->id)}}"
                                                    data-question="@lang('Are you sure to publish this comment')?"><i class="las la-check-double"></i>
                                                    @lang('Publish')
                                                </button>
                                            @else
                                                <button class="btn btn-outline--danger btn-sm ms-1 confirmationBtn reject"
                                                data-action="{{route('admin.success.story.comment.status', $comment->id)}}"
                                                    data-question="@lang('Are you sure to unpublish this comment')?" "> <i class="las la-ban"></i> @lang('Unpublish') </button>
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
                @if ($storyComments->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($storyComments) @endphp
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <input type="hidden" name="id" id="comment_id">

                        <h5>@lang('Comment Details:')</h5>
                        <div class="comments my-3"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
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
        'use strict';
        $('.commentDtlBtn').on('click', function() {
            var modal = $('#modelId');
            modal.find(".comments").text($(this).data('comment'));
            modal.find('.modal-title').text($(this).data('title'));
            modal.modal('show');
        });
    </script>
@endpush

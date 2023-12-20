@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4 align-items-start">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="campaing-img">
                        <img src="{{ getImage(getFilePath('campaign') . '/' . $campaign->image, getFileSize('campaign')) }}">
                    </div>
                    <ul class="list-group list-grou-flush">
                        <li class="list-group-item d-flex    border-b justify-content-between">
                            <span class="text--muted">@lang('Title')</span>
                            <span>{{ __($campaign->title) }}</span>
                        </li>
                        <li class="list-group-item d-flex   border-b justify-content-between">
                            <span class="text--muted ">@lang('Category')</span>
                            <span>{{ __($campaign->category->name) }}</span>
                        </li>
                        <li class="list-group-item d-flex   border-b justify-content-between">
                            <span class="text--muted ">@lang('Deadline')</span>
                            <span>{{ showDateTime($campaign->deadline) }}</span>
                        </li>
                        <li class="list-group-item d-flex   border-b justify-content-between">
                            <span class="text--muted ">@lang('User')</span>
                            <span>{{ __($campaign->user->fullname) }}</span>
                        </li>
                        <li class="list-group-item d-flex  border-b justify-content-between">
                            <span class="text--muted ">@lang('Status')</span>
                            <div class="">@php echo $campaign->statusBadge; @endphp</div>
                        </li>
                        <li class="list-group-item d-flex  border-b justify-content-between">
                            <span class="text--muted ">@lang('Campaign Featured')</span>
                            <div>
                                @if ($campaign->featured)
                                    <span class="badge badge--successs">@lang('Yes')</span>
                                    @else
                                    <span class="badge badge--danger">@lang('No')</span>
                                @endif
                            </div>
                        </li>
                    </ul>
                    <div class="mt-3">
                        @if ($campaign->status==Status::PENDING)
                        <button type="button" class="btn btn-sm btn-outline--success  confirmationBtn"
                        data-action="{{ route('admin.fundrise.approve.reject', ['status' => Status::CAMPAIGN_APPROVED, 'id' => $campaign->id]) }}"
                        data-question="@lang('Are you sure to approve this campaign')?">
                        <i class="la la-check"></i>@lang('Approve')
                        </button>
                        <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                            data-action="{{ route('admin.fundrise.approve.reject', ['status' => Status::REJECTED, 'id' => $campaign->id]) }}"
                            data-question="@lang('Are you sure to reject this campaign')?">
                            <i class="la la-times"></i>@lang('Reject')
                        </button>
                        @endif
                        @if ($campaign->status ==Status::CAMPAIGN_APPROVED)
                            @if (!$campaign->featured)
                                <button type="button" class="btn btn-sm btn-outline--dark confirmationBtn"
                                data-action="{{ route('admin.fundrise.make.featured',$campaign->id) }}"
                                data-question="@lang('Are you sure to fetured this campaign')?">
                                <i class="las la-arrow-alt-circle-right"></i>@lang('Feature It')
                            </button>
                            @else
                        <button type="button" class="btn btn-sm btn-outline--warning confirmationBtn"
                            data-action="{{ route('admin.fundrise.make.featured',$campaign->id) }}"
                            data-question="@lang('Are you sure to un-fetured this campaign')?">
                            <i class="las la-arrow-alt-circle-left"></i>@lang('Unfeature It')
                        </button>
                        @endif
                    @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                   <h5> @lang('Donation Details')</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-grou-flush">
                        <li class="list-group-item d-flex    border-b justify-content-between">
                            <span class="text--muted">@lang('Goal Amount')</span>
                            <span>{{ showAmount($campaign->goal) }} {{ __($general->cur_text) }}</span>
                        </li>
                        <li class="list-group-item d-flex    border-b justify-content-between">
                            <span class="text--muted">@lang('Already Collected Amont')</span>
                            <span>{{ showAmount($donate) }} {{ __($general->cur_text) }}</span>
                        </li>
                        <li class="list-group-item d-flex    border-b justify-content-between">
                            <span class="text--muted">@lang('Number of Donors')</span>
                            <span>{{ $campaign->donation_count }}</span>
                        </li>
                        <li class="list-group-item d-flex    border-b justify-content-between">
                            <span class="text--muted">@lang('Donation Progress %')</span>
                            <span> {{ getAmount($percent) }}%</span>
                        </li>
                        <li class="list-group-item d-flex   border-b justify-content-between">
                            <span class="text--muted ">@lang('Donation Progress')</span>
                            <div class="w-50">
                                <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar" style="width: {{ getAmount($percent) }}%">{{ getAmount($percent) }}%</div>
                                  </div>
                            </div>
                        </li>
                    </ul>
                    <ul class="list-group list-grou-flush">
                        <h6 class="mt-3">@lang('Latest Donor')</h6>
                        @forelse ($campaign->donation as $item)
                        <li class="list-group-item d-flex    border-b justify-content-between">
                            <span class="text--muted">{{ $item->fullname }}</span>
                            <span> {{ getAmount($item->donation) }} {{ __($general->cur_text) }}</span>
                        </li>
                        @empty
                        <h5 class="text-muted">@lang('No donors yet')</h5>
                        @endforelse
                        <a href="{{ route('admin.donation.campaign.wise', $campaign->id) }}" class="text--primary">@lang('View all')</a>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                   <h5> @lang('Relevent Image')</h5>
                </div>
                <div class="card-body d-flex gap-2 flex-wrap">
                    @foreach ($campaign->proof_images as $images)
                    @if (explode('.', $images)[1] != 'pdf')
                    <div class="gallery-card">
                        <a href="{{ asset(getFilePath('proof') . '/' . $images) }}" class="view-btn"
                            data-rel="lightcase:myCollection"><i class="las la-plus"></i></a>
                        <div class="gallery-card__thumb">
                            <img src="{{ asset(getFilePath('proof') . '/' . $images) }}" class="w-100">
                        </div>
                    </div>
                    @endif
                @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                   <h5> @lang('Relevent Document')</h5>
                </div>
                <div class="card-body">
                    @foreach ($campaign->proof_images as $pdfFiles)
                        @if (explode('.', $pdfFiles)[1] == 'pdf')
                            <iframe class="iframe" src="{{ asset(getFilePath('proof') . '/' . $pdfFiles) }}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope picture-in-picture" allowfullscreen></iframe>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                   <h5> @lang('Description')</h5>
                </div>
                <div class="card-body">
                    @php echo $campaign->description @endphp
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                   <h5> @lang('Comment')</h5>
                </div>
                <div class="card-body">
                    @if ($campaign->comments->count())
                        <div class="table-responsive--md table-responsive">
                            <table class=" table align-items-center table--light">
                                <thead>
                                    <tr>
                                        <th>@lang('Fullname') | @lang('Email')</th>
                                        <th>@lang('Comment')</th>
                                        <th>@lang('Created At')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($campaign->comments as $comment)
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ __($comment->fullname) }}</span>
                                                <span class="d-block">{{ $comment->email }}</span>
                                            </td>
                                            <td>{{ strLimit($comment->comment, 30) }}</td>
                                            <td>
                                                {{ showDateTime($comment->created_at) }}
                                                <span class="d-block">{{ diffForHumans($comment->created_at) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($campaign->comments->count() > 1)
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.fundrise.comments') }}?campaign_id={{ $campaign->id }}"
                                        class="btn btn--primary me-md-2" type="button"><i
                                            class="las la-comment-dots"></i> @lang('See All')</a>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-center border-1"> @lang('No comment yet')</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.fundrise.index') }}" />
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/lightcase.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/animate.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/lightcase.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';

        $('a[data-rel^=lightcase]').lightcase();



        $('.approve').click(function() {
            $('#approveModal').attr('action', $(this).data('action'));
            $('#approveModal').modal('show');
        });

        $('.reject').click(function() {
            $('#rejectModal').attr('action', $(this).data('action'));
            $('#rejectModal').modal('show');
        });

    </script>
@endpush

@push('style')
<style>
    .campaing-img {
        text-align: center;
    }
    .campaing-img img {
        width: 100px;
        height: 100px;
        border-radius: 500%;
        object-fit: cover;
    }
    .iframe {
        width: 100%;
        max-height: 350px;
    }
    .list-group-item{
        border:0;
    }
    .border-b{
        border-bottom: 1px solid rgba(0,0,0,.125);
    }

    .gallery-card{
        max-width: 180px;
        margin-bottom: 10px;
        border: 3px solid #ddd;
        border-radius: 5px;
    }
    .gallery-card__thumb img {
        object-fit: cover;
        object-position: center;
    }
    iframe.iframe {
    min-height: 300px;
}


</style>
@endpush

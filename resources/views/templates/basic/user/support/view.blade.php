@extends($activeTemplate . 'layouts.' . $layout)

@section('content')

    <section class="pt-120 pb-120">
        <div class="container">
            <div class="row gy-4 justify-content-center">
                <div class="col-md-10">
                    <div class="card custom--card">
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                            <h5 class="card-title">
                                @php echo $myTicket->statusBadge; @endphp
                                [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                            </h5>
                            @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                                <button class="btn btn-danger close-button btn-sm confirmationBtn" type="button" data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}"><i class="la la-lg la-times-circle"></i>
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row ">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea name="message" class="form-control form--control" rows="4" placeholder="@lang('Your Reply Here..')">{{ old('message') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="d-flex justify-content-between mb-2 flex-wrap">
                                        <div class=" pt-2 attachment-text"> <label class="form-label">@lang('Attachments')</label> <small class="text-danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}</small></div>
                                        <div class="text-end attachment-button">
                                            <a href="javascript:void(0)" class="btn cmn-btn btn-sm addFile"><i class="la la-plus"></i> @lang('Add New')</a>
                                        </div>
                                    </div>
                                    <input type="file" name="attachments[]" class="form-control form--control" />
                                    <div id="fileUploadsContainer"></div>
                                    <small class="my-2 ticket-attachments-message text-muted">
                                        @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                    </small>
                                </div>
                                <button type="submit" class="btn cmn-btn w-100"> <i class="la la-reply"></i> @lang('Reply')</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-10">
                    <div class="card custom--card">
                        <div class="card-body">
                            @foreach ($messages as $message)
                                @if ($message->admin_id == 0)
                                    <div class="row border border-primary border-radius-3 my-3 py-3 mx-2">
                                        <div class="col-md-3 border-end text-end">
                                            <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                        </div>
                                        <div class="col-md-9">
                                            <small class="text-muted fw-bold my-3">
                                                @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</small>
                                            <p>{{ $message->message }}</p>
                                            @if ($message->attachments->count() > 0)
                                                <div class="mt-2">
                                                    @foreach ($message->attachments as $k => $image)
                                                        <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="me-3"><i class="la la-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="row border border-warning border-radius-3 my-3 py-3 mx-2" style="background-color: #ffd96729">
                                        <div class="col-md-3 border-end text-end">
                                            <h5 class="my-3">{{ $message->admin->name }}</h5>
                                            <p class="lead text-muted">@lang('Staff')</p>
                                        </div>
                                        <div class="col-md-9">
                                            <small class="text-muted fw-bold my-3">
                                                @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</small>
                                            <p>{{ $message->message }}</p>
                                            @if ($message->attachments->count() > 0)
                                                <div class="mt-2">
                                                    @foreach ($message->attachments as $k => $image)
                                                        <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="me-3"><i class="la la-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <x-confirmation-modal />

@endsection


@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 4) {
                    notify('error', 'You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="input-group my-3">
                        <input type="file" name="attachments[]" class="form-control form--control" required />
                        <button type="submit" class="input-group-text btn-danger remove-btn"><i class="la la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.input-group').remove();
            });
        })(jQuery);
    </script>
@endpush

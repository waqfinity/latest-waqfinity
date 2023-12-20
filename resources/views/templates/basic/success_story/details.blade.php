@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <!-- blog-details-section start -->
    <section class="blog-details-section pt-150 pb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8">
                    <div class="blog-details-wrapper">
                        <div class="blog-details__thumb">
                            <img src="{{ getImage(getFilePath('success') . '/' . $story->image), getFileSize('success') }}"
                                alt="image">
                            <div class="post__date">
                                <span class="date">{{ showDateTime($story->created_at, 'd') }}</span>
                                <span class="month">{{ showDateTime($story->created_at, 'M') }}</span>
                            </div>
                            <div class="post__date_right">
                                <span class="date">@lang('View')</span>
                                <span class="month">{{ $story->view }}</span>
                            </div>
                        </div><!-- blog-details__thumb end -->
                        <div class="blog-details__content">
                            <h4 class="blog-details__title">{{ __($story->title) }}</h4>
                            <p class="text-justify show-read-more"> @php echo strip_tags($story->description); @endphp</p>
                        </div><!-- blog-details__content end -->
                        <div class="comments-area">
                            <h3 class="title">{{ $comments->count() }} @lang('comments')</h3>
                            <ul class="comments-list">
                                @forelse($comments as $comment)
                                    <li class="single-comment">
                                        <div class="single-comment__thumb">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <div class="single-comment__content">
                                            <h6 class="name">{{ __($comment->reviewer) }}</h6>
                                            <small class="date">{{ diffForHumans($comment->created_at) }}</small>
                                            <p class="text-justify">{{ __($comment->comment) }}</p>
                                        </div>
                                    </li><!-- single-review end -->
                                @empty
                                    <p>@lang('No comment found')</p>
                                @endforelse
                            </ul>
                        </div><!-- comments-area end -->
                        <div class="comment-form-area">
                            <h3 class="title">@lang('Leave a Comment')</h3>
                            <form class="comment-form" action="{{ route('success.story.comment', $story->id) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <input type="text" name="name" id="comment-name" value="{{ old('name') }}"
                                            placeholder="@lang('Enter Your Name')" class="form-control" required>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <input type="email" name="email" id="comment-email" value="{{ old('email') }}"
                                            placeholder="@lang('Enter Email Address')" class="form-control" required>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <textarea name="comment" id="message" placeholder="@lang('Write Comment Here..')" class="form-control" required> {{ old('comment') }}</textarea>
                                        <code>@lang('Note: Characters remaining') <span id="limit">400</span> </code>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="cmn-btn w-100">@lang('Submit')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="sidebar">
                        <div class="widget p-0">
                            <div class="widget-title">
                                <h5 class="text-white">@lang('Share The Story')</h5>
                            </div>
                            <div class="link-copy copy widget-body">
                                <input type="text" id="urlCopyId"
                                    value="{{ route('success.story.details', ['slug' => $story->slug, 'id' => $story->id]) }}"
                                    class="form-control">
                                <button type="button" class="copyText">@lang('Copy')</button>
                            </div>
                            <ul class="social-links widget-body">
                                <li class="facebook"><a
                                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                        target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                <li class="twitter"><a
                                        href="https://twitter.com/intent/tweet?text=Post and Share &amp;url={{ urlencode(url()->current()) }}"
                                        target="_blank"><i class="fab fa-twitter"></i></a></li>
                                <li class="linkedin"><a
                                        href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}"
                                        target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                                <li class="whatsapp"><a target="_blank"
                                        href="https://wa.me/?text={{ urlencode(url()->current()) }}"><i
                                            class="fab fa-whatsapp"></i></a></li>
                            </ul>
                        </div>
                        <div class="widget p-0">
                            <div class="widget-title">
                                <h5 class="text-white">@lang('Recent Stories')</h5>
                            </div>
                            <ul class="small-post-list widget-body">
                                @foreach ($recentStories as $recent)
                                    <li class="small-post">
                                        <div class="small-post__thumb"><img
                                                src="{{ getImage(getFilePath('success') . '/' . $recent->image) }}"
                                                alt="image"></div>
                                        <div class="small-post__content">
                                            <h5 class="post__title">
                                                <a href="{{ route('success.story.details', ['slug' => $recent->slug, 'id' => $recent->id]) }}">{{ strLimit($recent->title, 30) }}</a>
                                            </h5>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        'use strict';

        //copy-url
        $('.copyText').on('click', function() {
            var copyText = document.getElementById("urlCopyId");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            notify('success', 'URL copied successfully');
        })

        $('#message').on('keyup paste', function() {
            var text = $(this).val();
            $('#limit').text(400 - text.length);
            if (text.length >= 400) {
                var newStr = text.substring(0, 400);
                $(this).val(newStr);
                $('#limit').text(0);
            }
        })
    </script>
@endpush

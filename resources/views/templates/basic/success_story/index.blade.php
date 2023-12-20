@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-150 pb-150">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-xl-3 col-lg-4">
                            <div class="sidebar">
                                <div class="widget search--widget">
                                    <form class="search-form" method="GET" action="{{ route('success.story.archive') }}">
                                        <input type="text" name="search" id="search__field"
                                            value="{{ request()->search }}" placeholder="@lang('Search by title')..."
                                            class="form-control">
                                        <button type="submit" class="search-btn"><i class="las la-search"></i></button>
                                    </form>
                                </div><!-- widget end -->
                                <div class="widget">
                                    <div class="widget-title">
                                        <h5 class="">@lang('Categories')</h5>
                                    </div>
                                    <ul class="categories__list mt-2">
                                        <li class="categories__item">
                                            <a href="{{ route('success.story.archive') }}">@lang('All')</a>
                                        </li>
                                        @foreach ($categories as $category)
                                            <li class="categories__item @if ($category->name == request()->category_id) active @endif">
                                                <a href="{{ route('success.story.archive', ['slug' => $category->slug]) }}"
                                                    @if ($category->slug == request()->slug) class="active" @endif>{{ __($category->name) }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div><!-- widget end -->
                                <div class="widget">
                                    <div class="widget-title">
                                        <h5 class="">@lang('Archive')</h5>
                                    </div>
                                    <ul class="archive__list mt-2">
                                        @foreach ($archives as $archive)
                                            <li class="archive__item"><a
                                                    @if ($archive->yonth == request()->month && $archive->year == request()->year) class="active" @endif
                                                    href="{{ route('success.story.archive', ['month' => $archive->month, 'year' => $archive->year]) }}">
                                                    {{ __($archive->month) }} {{ __($archive->year) }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div><!-- widget end -->

                            </div><!-- sidebar end -->
                        </div>
                        <div class="col-xl-9 col-lg-8">
                            <div class="row gy-4 justify-content-center">
                                @forelse($stories as $story)
                                    <div class="col-xxl-4 col-xl-6 col-lg-12 col-sm-6">
                                        @include($activeTemplate . 'partials.story')
                                    </div>
                                @empty
                                    <div class="col-md-12 mb-30">
                                        <div class="empty-story">
                                            <h1>{{ __($emptyMessage) }}</h1>
                                            <p>@lang('Sorry, we couldn\'t find the data you were looking for').</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div><!-- row end -->

                            @if ($stories->hasPages())
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card-footer py-4">
                                            @php echo paginateLinks($stories) @endphp
                                        </div>
                                    </div>
                                </div><!-- row end -->
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .empty-story {
            width: 80%;
            margin: auto;
            text-align: center;
        }

        .empty-story h1 {
            font-size: 36px;
            color: #333;
        }

        .empty-story p {
            font-size: 24px;
            color: #666;
            margin-top: 20px;
        }

        .sidebar .widget+.widget {
            margin-top: unset !important;
        }
    </style>
@endpush

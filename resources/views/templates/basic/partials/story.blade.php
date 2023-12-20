<div class="blog-post hover--effect-1 has-link h-100">
    <a href="{{ route('success.story.details', ['slug' => $story->slug, 'id' => $story->id]) }}" class="item-link"></a>
    <div class="blog-post__thumb">
        <img src="{{ getImage(getFilePath('success') . '/' . $story->image) }}" alt="@lang('image')" class="w-100">
    </div>
    <div class="blog-post__content">
        <ul class="blog-post__meta mb-3">

            <li>
                <i class="las la-calendar-day"></i>
                <small>{{ showDateTime($story->created_at, 'Y-m-d') }}</small>
            </li>
        </ul>
        <h4 class="blog-post__title fw-bold">@php echo __(strLimit($story->title,50)) @endphp</h4>
        <p>@php echo __(strLimit($story->short_description,100)) @endphp</p>
       <span class="blog_link">@lang('See More') <i class="las la-arrow-right"></i></span>
    </div>
</div>

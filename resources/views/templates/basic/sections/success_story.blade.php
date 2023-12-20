@php
    $data    = getContent('success_story.content', true);
    $stories = App\Models\SuccessStory::orderBy('id', 'DESC')
        ->take(3)
        ->get();
@endphp
<!-- blog section start -->
<section class="pb-90 margin-top-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __($data->data_values->heading) }}</h2>
                    <p> {{ __($data->data_values->subheading) }}</p>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row gy-4 justify-content-center">
            @foreach ($stories as $story)
                <div class="col-lg-4 col-md-8 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
                    @include($activeTemplate . 'partials.story')
                </div>
            @endforeach
        </div>
    </div>
</section>

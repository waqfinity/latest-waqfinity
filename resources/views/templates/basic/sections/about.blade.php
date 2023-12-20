@php
    $about   = getContent('about.content', 'true');
    $aboutElement = getContent('about.element', false);
@endphp
<!-- about section start -->
<section class="pt-120 pb-120 about-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="about-thumb pe-lg-2">
                    <div class="thumb-one wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                        <img src=" {{ asset('assets/images/frontend/about/' . $about->data_values->image) }}"
                            alt="@lang('image')" class="w-100"></div>
                </div>
            </div>
            <div class="col-lg-6 mt-lg-0 mt-5 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.7s">
                <div class="section-content ps-lg-4">
                    <h2>{{ __($about->data_values->heading) }}</h2>
                    <p><?php echo $about->data_values->description ?></p>
                    <div class="btn-group justify-content-start mb-0">
                        <a href="{{ $about->data_values->button_url }} " class="cmn-btn mb-0">{{ __($about->data_values->button_name) }}</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- about section end -->

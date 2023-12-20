
<div class="row main-view justify-content-center">
    @forelse ($volunteers as $item)
        <div class="col-xxl-3 col-xl-4 col-md-4 col-sm-6 mb-30 wow fadeInUp" data-wow-duration="0.3s" data-wow-delay="0.3s">
            <div class="volunteer-card h-100">
                <div class="volunteer-card__thumb">
                    <img src="{{ getImage(getFilePath('volunteer') . '/' . $item->image, getFileSize('volunteer')) }}" class="w-100" @lang('Image')">
<!--                    <div class="volunteer-shape">
                        <img src="{{ asset($activeTemplateTrue . 'images/top-shape.png') }}" alt="image">
                    </div>-->
                </div>
                <div class="volunteer-card__content">
                    <h4 class="name">{{ __($item->fullname) }}</h4>
                    <span class="designation">@lang("Participate {$item->participated} Campaigns")</span>
                    <div class="designation"><small> @lang("From") : {{__(@$item->country)}}</small></div>
                </div>


            </div><!-- volunteer-card end -->
        </div>
    @empty
    <p class="text-center py-3">{{ __($emptyMessage) }}</p>
    @endforelse
</div>
@if ($volunteers->hasPages())
    <div class="col-lg-12">
        <div class="py-4">
            @php echo paginateLinks($volunteers) @endphp
        </div>
    </div>
@endif

@php
    $content = getContent('faq.content', true);
    $faqElements = getContent('faq.element', false, null, true);
@endphp
<section class="pt-120 pb-120" data-background="{{ getImage($activeTemplateTrue . 'images/faq.jpg') }}">
    <div class="container">
        <div class="row gy-sm-5 gy-4">
            @foreach ($faqElements as $item)
                @if ($loop->odd)
                    <div class="col-md-6">
                        <div class="faq-item">
                            <div class="faq-item__icon"><i class="fas fa-question"></i></div>
                            <div class="faq-item__content">
                                <h5 class="faq-item__title">{{ __(@$item->data_values->question) }}</h5>
                                <p class="faq-item__desc">{{ __(@$item->data_values->answer) }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-6">
                        <div class="faq-item">
                            <div class="faq-item__icon"><i class="fas fa-question"></i></div>
                            <div class="faq-item__content">
                                <h5 class="faq-item__title">{{ __(@$item->data_values->question) }}</h5>
                                <p class="faq-item__desc">{{ __(@$item->data_values->answer) }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

@php
    $purposeElement = getContent('purpose.element', false);
@endphp
<!-- about section start -->
<section class="@if(request()->routeIs('home')) pt-0 @else pt-120 @endif pb-120 about-section">
    <div class="container">
        @foreach ($purposeElement as $purpose)
            <div class="row gy-4">
                <div class=" col-lg-6 order-lg-2 {{ $loop->odd ? 'order-2 mt-lg-0' : 'order-1' }}">
                    <div class="{{ $loop->odd ? 'section-content pl-lg-4' : 'section-content pl-lg' }}">
                        <h2 class="section-title my-4">{{ __($purpose->data_values->heading) }}</h2>
                        <p class="text-justify"> @php echo  $purpose->data_values->description @endphp </p>
                    </div>
                </div>
                <div class="col-lg-6 order-1 {{ $loop->odd ? ' order-lg-2' : '' }}">
                    <img src="{{ getImage('assets/images/frontend/purpose/' . $purpose->data_values->image) }}"
                        alt="@lang('image')" class="w-100">
                </div>
            </div>
        @endforeach
    </div>
</section>

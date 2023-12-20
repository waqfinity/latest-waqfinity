    @php
        $content   = getContent('cta.content',true);
    @endphp
    @extends($activeTemplate . 'layouts.frontend')

    @section('content')
        <!-- volunteer section start -->
        <section class="pt-150 pb-150">
            <div class="container-fluid custom-container">
                <div class="filter_in_btn d-xl-none mb-4 d-flex justify-content-end">
                    <a href="javascript:void(0)"><i class="las la-filter"></i></a>
                </div>
                <div class="row gy-4 ">
                    <div class="col-xl-3">
                        <aside class="category-sidebar">
                            <div class="widget d-xl-none filter-top">
                                <div class="d-flex justify-content-between">
                                    <h5 class="title border-0 pb-0 mb-0">@lang('Filter')</h5>
                                    <div class="close-sidebar"><i class="las la-times"></i></div>
                                </div>
                            </div>
                            <div class="widget p-0">
                                <div class="widget-title">
                                    <h5>@lang('Search By Volunteer Name')</h5>
                                </div>
                                <div class="widget-body">
                                    <div>
                                        <label for="search">@lang('Volunteer Name') :</label>
                                        <div class="input-group">
                                            <input type="search" name="search" id="search" class="form-control">
                                            <button type="button" class="input-group-text" id="name-search">
                                                <i class="la la-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget p-0">
                                <div class="widget-title">
                                    <h5>@lang('Filter By Country')</h5>
                                </div>
                                <div class="widget-body">
                                    <div class="filter-color-area d-flex flex-wrap">
                                        <div class="row w-100">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Country Name')</label>
                                                    <select name="country_code" class="form-control w-100">
                                                        <option value="" selected disabled>@lang('Select one')</option>
                                                        @foreach ($countries as $key => $country)
                                                            <option value="{{ $key }}">
                                                                {{ __($country->country) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget p-0">
                                <div class="widget-body">
                                    <div class="filter-color-area d-flex flex-wrap">
                                        <div class="row w-100">
                                            <div class="col-md-12">
                                                <a href="{{route('volunteer.form')}}" class="cmn-btn w-100 text-center">{{__($content->data_values->button_title)}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>

                    <div class="col-xl-9">
                        @include($activeTemplate . 'partials.volunteer')
                    </div>
                </div>
            </div>
        </section>
        <!-- volunteer section end -->
    @endsection

    @push('script')
        <script>
            'use strict'

            let data = {};
            data.search = null;
            data.country_code = null;

            //Search by name
            $('#name-search').on('click', function() {
                data.search = $("input[name='search']").val();
                filterVolunteer();
            })
            $("select[name='country_code']").on('change', function() {
                data.country_code = $(this).find(":selected").val();
                filterVolunteer();
            })

            function filterVolunteer() {
                $.ajax({
                    url: "{{ route('volunteer.filter') }}",
                    method: 'GET',
                    data: data,
                    success: function(response) {
                        if(response.success){
                            $('.main-view').html(response.html)
                        }
                    },
                });
            }

        </script>
    @endpush

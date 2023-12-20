@extends($activeTemplate . 'layouts.app')
@section('panel')
    <div class="page-wrapper">
        @yield('content')
    </div>
@endsection

@push('script')
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.validate.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . '/js/bootstrap-fileinput.js') }}"></script>
@endpush


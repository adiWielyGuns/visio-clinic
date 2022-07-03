@extends('../layouts/base')

@section('body')
    @include('../layouts/pusher')
    <body class="">
        <div class="loading style-2" id="loading">
            <div class="loading-wheel"></div>
        </div>
        @include('../layouts/nav')
        @yield('content')
        @yield('style')

        {{-- <script src="{{ mix('dist/js/app.js') }}"></script> --}}
        <!-- END: JS Assets-->

        @yield('script_content')
    </body>
@endsection

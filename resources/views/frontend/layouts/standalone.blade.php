<!DOCTYPE html>
@langrtl
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endlangrtl
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', app_name()) | {{ app_name() }}</title>
        <meta name="description" content="@yield('meta_description', 'Arik Air Web Portals')">
        <meta name="author" content="@yield('meta_author', 'Asuquo Bartholomew Ikechukwu')">
        @yield('meta')

        @stack('before-styles')

    <!-- Favicons -->
        <link href="https://www.arikair.com/assets/images/favicon.ico" rel="icon">
        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
{{--        {{ style(mix('css/frontend.css')) }}--}}
    <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
{{--        <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/fontawesome-free/css/all.min.css') }}">--}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('adminlte3.2/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
{{--        <script src="https://unpkg.com/htmx.org@2.0.1" integrity="sha384-QWGpdj554B4ETpJJC9z+ZHJcA/i59TyjxEPXiiUgN2WmTyV5OEZWCD6gQhgkdpB/" crossorigin="anonymous"></script>--}}
        <style>
            .card {
                border-radius: 1rem;
            }

            /*.container-fluid {*/
            /*    zoom: 80%;*/
            /*}*/
        </style>
        @stack('after-styles')
    </head>
    <body class="hold-transition layout-top-nav" data-barba="wrapper">
        @include('includes.partials.read-only')

        <div id="app" class="wrapper">
            <!-- Preloader -->
{{--            <div class="preloader flex-column justify-content-center align-items-center">--}}
{{--                <img class="animation__wobble" src="{{ asset(config('view.logo.coloured')) }}" alt="ArikAirLogo" height="60" width="auto">--}}
{{--            </div>--}}



            <div class="content-wrapper" data-barba="container" data-barba-namespace="">
                @yield('content')
            </div>

        </div><!-- #app -->

        <!-- Scripts -->
        @stack('before-scripts')
{{--        {!! script(mix('js/manifest.js')) !!}--}}
{{--        {!! script(mix('js/vendor.js')) !!}--}}
{{--        {!! script(mix('js/frontend.js')) !!}--}}

        <!-- REQUIRED SCRIPTS -->
        <!-- jQuery -->
        <script src="{{ asset('adminlte3.2/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap -->
        <script src="{{ asset('adminlte3.2/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- overlayScrollbars -->
        <script src="{{ asset('adminlte3.2/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('adminlte3.2/js/adminlte.js') }}"></script>

{{--        <script src="{{ asset('adminlte3.2/js/demo.js') }}"></script>--}}


        @stack('after-scripts')

        @include('includes.partials.ga')

    </body>
</html>

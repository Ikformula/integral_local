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

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        {{--        <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/fontawesome-free/css/all.min.css') }}">--}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('adminlte3.2/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

        <style>
            .arik-card {
                border-top: 1px solid #34000D;
                border-bottom: 5px solid #032560;
            }

            .container-fluid {
                zoom: 80%;
            }
        </style>
        @stack('after-styles')
    </head>
    <body class="layout-navbar-fixed layout-fixed sidebar-mini accent-maroon sidebar-collapse" data-barba="wrapper">
    @include('includes.partials.read-only')

    <div id="app" class="wrapper">

        <div class="content-wrapperr" data-barba="container" data-barba-namespace="">
<div class="container-fluid" style="padding-left: 0;">
    <div class="row">
        <div class="col-12">
            @include('frontend.business_goals.quadrants._'.$business_area_id)
        </div>
    </div>
</div>

        </div>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark zoomed-out-content">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

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

    <script src="{{ asset('adminlte3.2/js/demo.js') }}"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />

    @stack('after-scripts')

    </body>
    </html>

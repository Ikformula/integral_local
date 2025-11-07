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
        <meta name="description" content="@yield('meta_description', 'Arik Air Integral')">
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

            .sidebar-link {
            display: block;
            padding: 0.1em 1rem;
            }

            .nav-sidebar .nav-link>.right, .nav-sidebar .nav-link>p>.right {
            position: absolute;
            right: 1rem;
            top: 0.3rem;
            }

            .nav-sidebar .menu-open > .nav-treeview {
                display: block;
                border-radius: 0 0 5px 5px;
                background-color: #7D2248 !important;
            }

            .arik-card {
                position: relative;
                border-top: 1px solid #34000D;
                padding-bottom: 30px; /* Adjust padding to account for the pseudo-element height */
            }

            .arik-card::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 30px; /* Total height of the tri-band border */
                background: linear-gradient(to bottom,
                    #34000D 27%,      /* Red color, 25% of total height */
                    #ffffff 27%,      /* White color starts here */
                    #ffffff 50%,      /* White color ends at 50% of total height */
                    #032560 50%);
                border-radius: 0 0 .25rem .25rem;
            }

            .container-fluid {
                zoom: 80%;
            }
        </style>
        @stack('after-styles')
    </head>

<body class="layout-navbar-fixed layout-fixed sidebar-mini accent-maroon @if(\Illuminate\Support\Facades\Route::currentRouteName() != 'frontend.index') sidebar-collapse @endif" data-barba="wrapper">
{{--    <body class="layout-navbar-fixed layout-fixed sidebar-mini accent-maroon " data-barba="wrapper">--}}
        @include('includes.partials.read-only')

        <div id="app" class="wrapper">
            <!-- Preloader -->
{{--            <div class="preloader flex-column justify-content-center align-items-center">--}}
{{--                <img class="animation__wobble" src="{{ asset(config('view.logo.coloured')) }}" alt="ArikAirLogo" height="60" width="auto">--}}
{{--            </div>--}}


            @include('frontend.includes.nav')
            @include('frontend.includes.sidebar')

            <div class="content-wrapper" data-barba="container" data-barba-namespace="">
            @include('includes.partials.logged-in-as')
                <!-- Content Header (Page header) -->
                <div class="content-header">

                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">@yield('title')</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);" onclick="history.back()"><strong><i class="fas fa-arrow-left-long"></i> Back</strong></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('frontend.index') }}">Home</a></li>
                                    <li class="breadcrumb-item active">@yield('title')</li>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                @yield('content')

            </div>

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark zoomed-out-content">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->

            <!-- Main Footer -->
            <footer class="main-footer">
                <strong>Copyright &copy; {{ date('Y') }} <a href="https://arikair.com">Arik Air</a>.</strong>
                All rights reserved.
                <div class="float-right d-none d-sm-inline-block">
                    <b>Version</b> 1.2.0
                </div>
            </footer>
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

        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
        />
        @include('includes.partials.messages-toastr')

        @stack('after-scripts')

        @include('includes.partials.ga')
    <script>
        jQuery(function($) {
            const currentUrl = window.location.href;
            const menuLinks = document.querySelectorAll('.nav-link');

            menuLinks.forEach(link => {
                if (link.href === currentUrl) {
                    link.classList.add('active');
                    const grandparentLi = link.parentElement.parentElement.parentElement;
                    grandparentLi.classList.add('menu-open');
                    const activeLink = grandparentLi.querySelector('.nav-link');
                    activeLink.classList.add('active');
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const startTime = Date.now();

            window.addEventListener("beforeunload", function () {
                const duration = Math.round((Date.now() - startTime) / 1000); // Time in seconds

                fetch("{{ route('user.activity.duration') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        duration: duration,
                        url: window.location.href
                    })
                });
            });
        });
    </script>

    </body>
</html>

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

        <style>
            .sidebar-link {
                display: block;
                padding: 0.1em 1rem;
            }

            .nav-sidebar .nav-link>.right, .nav-sidebar .nav-link>p>.right {
                position: absolute;
                right: 1rem;
                top: 0.3rem;
            }

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
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="{{ asset(config('view.logo.coloured')) }}" alt="ArikAirLogo" height="60" width="auto">
        </div>


    @include('frontend.includes.nav')
    @include('frontend.includes.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper iframe-mode" data-widget="iframe" data-loading-screen="750">
        @include('includes.partials.logged-in-as')
        <div class="nav navbar navbar-expand navbar-white navbar-light border-bottom p-0">
            <div class="nav-item dropdown">
                <a class="nav-link bg-danger dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Close</a>
                <div class="dropdown-menu mt-0">
                    <a class="dropdown-item" href="#" data-widget="iframe-close" data-type="all">Close All</a>
                    <a class="dropdown-item" href="#" data-widget="iframe-close" data-type="all-other">Close All Other</a>
                </div>
            </div>
            <a class="nav-link bg-light" href="#" data-widget="iframe-scrollleft"><i class="fas fa-angle-double-left"></i></a>
            <ul class="navbar-nav overflow-hidden" role="tablist"></ul>
            <a class="nav-link bg-light" href="#" data-widget="iframe-scrollright"><i class="fas fa-angle-double-right"></i></a>
            <a class="nav-link bg-light" href="#" data-widget="iframe-fullscreen"><i class="fas fa-expand"></i></a>
        </div>
        <div class="tab-content">
            <div class="tab-empty">
{{--                <h2 class="display-4">No tab selected!</h2>--}}
                <div class="container-fluid my-1">
                    <div class="row mb-4">
                        <div class="col">
                            <div class="card mb-2 bg-gradient-dark">
                                <img class="card-img-top rounded d-md-none" src="{{ asset('img/frontend/airplane-wing-md.jpg') }}" alt="Bg Welcome Image">
                                <img class="card-img-top rounded d-none d-md-block" src="{{ asset('img/frontend/airplane-wing-md.jpg') }}" alt="Bg Welcome Image">
                                <div class="card-img-overlay d-flex flex-column justify-content-end">
                                    <h5 class="card-title text-primary text-white">Welcome,</h5>
                                    <p class="card-text text-white pb-2 pt-1">{{ $logged_in_user->name }}</p>
                                    {{--                    <a href="#" class="text-white">Last update 2 mins ago</a>--}}
                                </div>
                            </div>
                        </div><!--col-->
                    </div><!--row-->




                    <div class="invoice bg-gradient-light rounded p-3 mb-3">
                        <!-- title row -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <h6>
                                    <i class="fas fa-globe"></i> App Navigation Links
                                </h6>
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($menus as $menu)
                                    @if(isset($menu['links']))
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card ">
                                                    <div class="card-header border-bottom-0">
                                                        <h3 class="card-title">
                                                            <i class="{{ $menu['icon'] }}"></i>
                                                            {{ $menu['title'] }}
                                                        </h3>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        @foreach($menu['links'] as $link)
                                                            <a class="btn btn-app" href="{{ $link['link'] }}">
                                                                {{--                                                            <span class="badge bg-success">300</span>--}}
                                                                <i class="{{ $link['icon'] }}"></i> {{ $link['title'] }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @if(isset($menu['sidebar_only']) && !$menu['sidebar_only'])
                                            <a href="{{ $menu['link'] }}" class="btn btn-app bg-maroon">
                                                {{--                                        <span class="badge bg-info">12</span>--}}
                                                <i class="{{ $menu['icon'] }}"></i>  {{ $menu['title'] }}
                                            </a>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="tab-loading">
{{--                <div>--}}
{{--                    <h2 class="display-4">Tab is loading <i class="fa fa-sync fa-spin"></i></h2>--}}
{{--                </div>--}}
                <div class="preloader flex-column justify-content-center align-items-center">
                    <img class="animation__wobble" src="{{ asset(config('view.logo.coloured')) }}" alt="ArikAirLogo" height="60" width="auto">
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->

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

    <script src="{{ asset('adminlte3.2/js/demo.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('adminlte3.2/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

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

    </body>
    </html>


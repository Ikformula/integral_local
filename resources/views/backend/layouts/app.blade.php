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
    <title>@yield('title', app_name())</title>
    <meta name="description" content="@yield('meta_description', 'Arik Integral Admin')">
    <meta name="author" content="@yield('meta_author', 'Asuquo Bartholomew Ikechukwu')">
    @yield('meta')

    @stack('before-styles')

    <!-- Otherwise apply the normal LTR layouts -->
{{--    {{ style(mix('css/backend.css')) }}--}}
    <link href="{{ asset('css/backend.css') }}" rel="stylesheet">

    <style>

        [type=search]{outline-offset:-2px;-webkit-appearance:none;}

        .sidebar .form-inline .input-group{width:100%;-ms-flex-wrap:nowrap;flex-wrap:nowrap;}
        .sidebar-search-results{position:relative;display:none;width:100%;}
        .sidebar-search-results .search-title{margin-bottom:-.1rem;}
        .sidebar-search-results .list-group{position:absolute;width:100%;z-index:1039;}
        .sidebar-search-results .list-group>.list-group-item{padding:.375rem .75rem;}
        .sidebar-search-results .list-group>.list-group-item:first-child{margin-top:0;border-top:0;border-top-left-radius:0;border-top-right-radius:0;}
        .sidebar-search-results .search-path{font-size:80%;}

    </style>
    @stack('after-styles')
</head>

{{--
     * CoreUI BODY options, add following classes to body to change options
     * // Header options
     * 1. '.header-fixed'					- Fixed Header
     *
     * // Sidebar options
     * 1. '.sidebar-fixed'					- Fixed Sidebar
     * 2. '.sidebar-hidden'				- Hidden Sidebar
     * 3. '.sidebar-off-canvas'		    - Off Canvas Sidebar
     * 4. '.sidebar-minimized'			    - Minimized Sidebar (Only icons)
     * 5. '.sidebar-compact'			    - Compact Sidebar
     *
     * // Aside options
     * 1. '.aside-menu-fixed'			    - Fixed Aside Menu
     * 2. ''			    - Hidden Aside Menu
     * 3. '.aside-menu-off-canvas'	        - Off Canvas Aside Menu
     *
     * // Breadcrumb options
     * 1. '.breadcrumb-fixed'			    - Fixed Breadcrumb
     *
     * // Footer options
     * 1. '.footer-fixed'					- Fixed footer
--}}
<body class="app header-fixed sidebar-fixed aside-menu-off-canvas sidebar-lg-show">
    @include('backend.includes.header')

    <div class="app-body">
        @include('backend.includes.sidebar')

        <main class="main">
            @include('includes.partials.read-only')
            @include('includes.partials.logged-in-as')
{{--            {!! Breadcrumbs::render() !!}--}}
            <div class="my-4"></div>

            <div class="container-fluid">
                <div class="animated fadeIn">
                    <div class="content-header">
                        @yield('page-header')
                    </div><!--content-header-->

                    @include('includes.partials.messages')
                    @yield('content')
                </div><!--animated-->
            </div><!--container-fluid-->
        </main><!--main-->

        @include('backend.includes.aside')
    </div><!--app-body-->

{{--    @include('backend.includes.footer')--}}

    <!-- Scripts -->
    @stack('before-scripts')
    {!! script(mix('js/manifest.js')) !!}
    {!! script(mix('js/vendor.js')) !!}
    {!! script(mix('js/backend.js')) !!}
    @stack('after-scripts')
</body>
</html>

<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 bg-navy sidebar-dark-maroon">
    <!-- Brand Logo -->
    <a href="{{ route('frontend.index') }}" class="brand-link bg-navy">
        <img src="{{ asset(config('view.logo.gray')) }}" alt="Arik Air Logo" class="brand-image " style="opacity: .8">
        <span class="brand-text font-weight-light">{{ app_name() }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ $logged_in_user->picture }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('frontend.user.profile.editIDcard') }}?staff_ara_id={{ $logged_in_user->staff_member->staff_ara_id ?? '0000' }}" class="d-block text-white">{{ $logged_in_user->full_name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        @if($logged_in_user->isAdmin())
        <form action="{{ route('admin.auth.search') }}" method="GET" class="form-inline" target="_blank">
        <div class="input-group" data-widget="sidebar-searchh">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search ARA IDs, first name, surname, email" aria-label="Search" name="q" title="Search users by ARA IDs, first name, surname, email. Will only bring results of those who have signed in on Integral at least once">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </form>
        @endif

        <!-- Sidebar Menu -->
        <nav class="mt-2">
{{--            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" hx-boost="true">--}}
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @foreach($menus as $menu)
                    @if(isset($menu['links']))
                    <li class="nav-item">
                        <a hreffff="{{ $menu['link'] }}" class="nav-link sidebar-link">
                            <i class="nav-icon {{ $menu['icon'] }}"></i>
                            <p>
                                {{ $menu['title'] }}

                                @if(isset($menu['badge_text']))
                                    <span class="badge badge-{{ $menu['badge_colour'] }}">{!! $menu['badge_text'] !!}</span>
                                @endif
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                    @foreach($menu['links'] as $link)
                            <li class="nav-item">
                                <a href="{{ $link['link'] }}" class="nav-link sidebar-link" @if(isset($menu['attributes'])){{ $menu['attributes'] }} @endif>
                                    <i class="{{ $link['icon'] }} nav-icon"></i>
                                    <p>{{ $link['title'] }}
                                    @if(isset($link['badge_text']))
                                        <span class="badge badge-{{ $link['badge_colour'] }} right">{!! $link['badge_text'] !!}</span>
                                    @endif
                                    </p>
                                </a>
                            </li>
                    @endforeach
                        </ul>
                    </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ $menu['link'] }}" class="nav-link sidebar-link" @if(isset($menu['attributes'])){{ $menu['attributes'] }} @endif>
                                <i class="nav-icon {{ $menu['icon'] }}"></i>
                                <p>
                                    {{ $menu['title'] }}

                                    @if(isset($menu['badge_text']))
                                        <span class="badge badge-{{ $menu['badge_colour'] }} right">{!! $menu['badge_text'] !!}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                    @endif

                @endforeach

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

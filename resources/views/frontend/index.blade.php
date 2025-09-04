@extends('frontend.layouts.app')

@section('title', ' Dashboard')

@section('content')

    <section class="content">
        <div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <div class="card mb-2 bg-gradient-dark">
                <img class="card-img-top rounded d-md-none" src="https://res.cloudinary.com/anya-ng/image/upload/v1750252570/Arik-plane_thhnnw.jpg" alt="Bg Welcome Image">
                <img class="card-img-top rounded d-none d-md-block" src="https://res.cloudinary.com/anya-ng/image/upload/v1750252570/Arik-plane_thhnnw.jpg" alt="Bg Welcome Image">
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
                                                        @if(isset($menu['badge_text']))
                                                            <span class="badge badge-{{ $menu['badge_colour'] }}">{!! $menu['badge_text'] !!}</span>
                                                        @endif
                                                    </h3>
                                                </div>
                                                <div class="card-body p-0">
                            @foreach($menu['links'] as $link)
                                                        <a class="btn btn-app" href="{{ $link['link'] }}">
                                                            @if(isset($link['badge_text']))
                                                                <span class="badge badge-{{ $link['badge_colour'] }} right">{!! $link['badge_text'] !!}</span>
                                                            @endif
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
                                        @if(isset($menu['badge_text']))
                                            <span class="badge badge-{{ $menu['badge_colour'] }}">{!! $menu['badge_text'] !!}</span>
                                        @endif
                                        <i class="{{ $menu['icon'] }}"></i>  {{ $menu['title'] }}
                                    </a>
                            @endif
                        @endif
                    @endforeach
                        </div>
                    </div>
            </div>

        </div>
    </section>
@endsection

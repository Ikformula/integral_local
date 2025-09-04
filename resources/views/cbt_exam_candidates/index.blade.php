@extends('frontend.layouts.app')

@section('content')

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

    @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {!! session('success_message') !!}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

                    @include('cbt_exam_candidates.list')
                </div>
            </div>
        </div>
    </section>
@endsection

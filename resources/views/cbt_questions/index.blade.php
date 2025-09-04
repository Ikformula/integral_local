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

    <div class="card text-bg-theme">

        <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">CBT Questions</h4>
            <div>
                <a href="{{ route('cbt_questions.cbt_question.create') }}" class="btn btn-secondary" title="Create New CBT Question">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>

        @if(count($cbtQuestions) == 0)
            <div class="card-body text-center">
                <h4>No CBT Questions Available.</h4>
            </div>
        @else
        <div class="card-body p-0">
            @include('cbt_questions.list-table')

            {!! $cbtQuestions->links('pagination') !!}
        </div>

        @endif

    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

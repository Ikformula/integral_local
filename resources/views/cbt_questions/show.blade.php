@extends('frontend.layouts.app')

@section('content')

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($title) ? $title : 'CBT Question' }}</h4>
        <div>
            <form method="POST" action="{!! route('cbt_questions.cbt_question.destroy', $cbtQuestion->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('cbt_questions.cbt_question.edit', $cbtQuestion->id ) }}" class="btn btn-primary" title="Edit CBT Question">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="Delete CBT Question" onclick="return confirm(&quot;Click Ok to delete CBT Question.?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('cbt_questions.cbt_question.index') }}" class="btn btn-primary" title="Show All CBT Question">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('cbt_questions.cbt_question.create') }}" class="btn btn-secondary" title="Create New CBT Question">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">Question</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtQuestion->question }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">CBT Subject</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($cbtQuestion->CbtSubject)->id }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Created At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtQuestion->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Updated At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtQuestion->updated_at }}</dd>

        </dl>

    </div>
</div>

                    @php($cbtOptions = $cbtQuestion->cbtOptions)
                    <div class="card text-bg-theme">

                        <div class="card-header d-flex justify-content-between align-items-center p-3">
                            <h4 class="m-0">Options</h4>
{{--                            <div>--}}
{{--                                <a href="{{ route('cbt_options.cbt_option.create') }}" class="btn btn-secondary" title="Create New CBT Option">--}}
{{--                                    <span class="fa-solid fa-plus" aria-hidden="true"></span>--}}
{{--                                </a>--}}
{{--                            </div>--}}
                        </div>

                        @if(count($cbtOptions) == 0)
                            <div class="card-body text-center">
                                <h4>No CBT Options Available.</h4>
                            </div>
                        @else
                            <div class="card-body p-0">
                                @include('cbt_options.list-table')

                            </div>

                        @endif

                    </div>


                </div>
            </div>
        </div>
    </section>
@endsection

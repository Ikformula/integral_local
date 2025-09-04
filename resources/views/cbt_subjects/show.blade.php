@extends('frontend.layouts.app')

@section('content')

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($cbtSubject->name) ? $cbtSubject->name : 'CBT Subject' }}</h4>
        <div>
            <form method="POST" action="{!! route('cbt_subjects.cbt_subject.destroy', $cbtSubject->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('cbt_subjects.cbt_subject.edit', $cbtSubject->id ) }}" class="btn btn-primary" title="Edit CBT Subject">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="Delete CBT Subject" onclick="return confirm(&quot;Click Ok to delete CBT Subject.?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('cbt_subjects.cbt_subject.index') }}" class="btn btn-primary" title="Show All CBT Subject">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('cbt_subjects.cbt_subject.create') }}" class="btn btn-secondary" title="Create New CBT Subject">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">Name</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtSubject->name }}</dd>
{{--            <dt class="text-lg-end col-lg-2 col-xl-3">Created At</dt>--}}
{{--            <dd class="col-lg-10 col-xl-9">{{ $cbtSubject->created_at }}</dd>--}}
{{--            <dt class="text-lg-end col-lg-2 col-xl-3">Updated At</dt>--}}
{{--            <dd class="col-lg-10 col-xl-9">{{ $cbtSubject->updated_at }}</dd>--}}

        </dl>

    </div>
</div>
                    @php $cbtQuestions = $cbtSubject->cbtQuestions; @endphp
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

                            </div>

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

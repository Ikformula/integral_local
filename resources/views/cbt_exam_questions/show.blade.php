@extends('frontend.layouts.app')

@section('content')

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($title) ? $title : 'CBT Exam Question' }}</h4>
        <div>
            <form method="POST" action="{!! route('cbt_exam_questions.cbt_exam_question.destroy', $cbtExamQuestion->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('cbt_exam_questions.cbt_exam_question.edit', $cbtExamQuestion->id ) }}" class="btn btn-primary" title="Edit CBT Exam Question">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="Delete CBT Exam Question" onclick="return confirm(&quot;Click Ok to delete CBT Exam Question.?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('cbt_exam_questions.cbt_exam_question.index') }}" class="btn btn-primary" title="Show All CBT Exam Question">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('cbt_exam_questions.cbt_exam_question.create') }}" class="btn btn-secondary" title="Create New CBT Exam Question">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">CBT Exam</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($cbtExamQuestion->CbtExam)->title }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">CBT Question</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($cbtExamQuestion->CbtQuestion)->id }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Created At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExamQuestion->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Updated At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExamQuestion->updated_at }}</dd>

        </dl>

    </div>
</div>

                </div>
            </div>
        </div>
    </section>
@endsection

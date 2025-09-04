@extends('frontend.layouts.app')

@section('content')

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($title) ? $title : 'CBT Question Response' }}</h4>
        <div>
            <form method="POST" action="{!! route('cbt_question_responses.cbt_question_response.destroy', $cbtQuestionResponse->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('cbt_question_responses.cbt_question_response.edit', $cbtQuestionResponse->id ) }}" class="btn btn-primary" title="Edit CBT Question Response">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="Delete CBT Question Response" onclick="return confirm(&quot;Click Ok to delete CBT Question Response.?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('cbt_question_responses.cbt_question_response.index') }}" class="btn btn-primary" title="Show All CBT Question Response">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('cbt_question_responses.cbt_question_response.create') }}" class="btn btn-secondary" title="Create New CBT Question Response">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">CBT Exam Question</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($cbtQuestionResponse->CbtExamQuestion)->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">CBT Option</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($cbtQuestionResponse->CbtOption)->body }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">CBT Exam Candidate</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($cbtQuestionResponse->CbtExamCandidate)->email }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Is History</dt>
            <dd class="col-lg-10 col-xl-9">{{ ($cbtQuestionResponse->is_history) ? 'Yes' : 'No' }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Created At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtQuestionResponse->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Updated At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtQuestionResponse->updated_at }}</dd>

        </dl>

    </div>
</div>

                </div>
            </div>
        </div>
    </section>
@endsection

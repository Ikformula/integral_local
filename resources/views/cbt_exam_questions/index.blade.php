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
            <h4 class="m-0">CBT Exam Questions</h4>
            <div>
                <a href="{{ route('cbt_exam_questions.cbt_exam_question.create') }}" class="btn btn-secondary" title="Create New CBT Exam Question">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>

        @if(count($cbtExamQuestions) == 0)
            <div class="card-body text-center">
                <h4>No CBT Exam Questions Available.</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>CBT Exam</th>
                            <th>CBT Question</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($cbtExamQuestions as $cbtExamQuestion)
                        <tr>
                            <td class="align-middle">{{ optional($cbtExamQuestion->CbtExam)->title }}</td>
                            <td class="align-middle">{{ optional($cbtExamQuestion->CbtQuestion)->id }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('cbt_exam_questions.cbt_exam_question.destroy', $cbtExamQuestion->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('cbt_exam_questions.cbt_exam_question.show', $cbtExamQuestion->id ) }}" class="btn btn-info" title="Show CBT Exam Question">
                                            <span class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('cbt_exam_questions.cbt_exam_question.edit', $cbtExamQuestion->id ) }}" class="btn btn-primary" title="Edit CBT Exam Question">
                                            <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete CBT Exam Question" onclick="return confirm(&quot;Click Ok to delete CBT Exam Question.&quot;)">
                                            <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                                        </button>
                                    </div>

                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

            {!! $cbtExamQuestions->links('pagination') !!}
        </div>

        @endif

    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

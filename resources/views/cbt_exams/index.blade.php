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
            <h4 class="m-0">CBT  Exams</h4>
            <div>
                <a href="{{ route('cbt_exams.cbt_exam.create') }}" class="btn btn-secondary" title="Create New CBT Exam">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>

        @if(count($cbtExams) == 0)
            <div class="card-body text-center">
                <h4>No CBT Exams Available.</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Start At</th>
                            <th>Duration In Minutes</th>
                            <th>Creator User</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($cbtExams as $cbtExam)
                        <tr>
                            <td class="align-middle">{{ $cbtExam->title }}</td>
                            <td class="align-middle">{{ $cbtExam->start_at }}</td>
                            <td class="align-middle">{{ $cbtExam->duration_in_minutes }}</td>
                            <td class="align-middle">{{ optional($cbtExam->creatorUser)->full_name }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('cbt_exams.cbt_exam.destroy', $cbtExam->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('cbt_exams.cbt_exam.show', $cbtExam->id ) }}" class="btn btn-info" title="Show CBT Exam">
                                            <span class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('cbt_exams.cbt_exam.edit', $cbtExam->id ) }}" class="btn btn-primary" title="Edit CBT Exam">
                                            <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete CBT Exam" onclick="return confirm(&quot;Click Ok to delete CBT Exam.&quot;)">
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

            {!! $cbtExams->links('pagination') !!}
        </div>

        @endif

    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@extends('frontend.layouts.app')

@section('content')

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($title) ? $title : 'CBT Exam Candidate' }}</h4>
        <div>
            <form method="POST" action="{!! route('cbt_exam_candidates.cbt_exam_candidate.destroy', $cbtExamCandidate->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('cbt_exam_candidates.cbt_exam_candidate.edit', $cbtExamCandidate->id ) }}" class="btn btn-primary" title="Edit CBT Exam Candidate">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="Delete CBT Exam Candidate" onclick="return confirm(&quot;Click Ok to delete CBT Exam Candidate.?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('cbt_exam_candidates.cbt_exam_candidate.index') }}" class="btn btn-primary" title="Show All CBT Exam Candidate">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('cbt_exam_candidates.cbt_exam_candidate.create') }}" class="btn btn-secondary" title="Create New CBT Exam Candidate">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">Email</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExamCandidate->email }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Staff Ara</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($cbtExamCandidate->staffAra)->id }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">CBT Exam</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($cbtExamCandidate->CbtExam)->title }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Surname</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExamCandidate->surname }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">First Name</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExamCandidate->first_name }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Other Names</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExamCandidate->other_names }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Age</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExamCandidate->age }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Gender</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExamCandidate->gender }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">State</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExamCandidate->state }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Address</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExamCandidate->address }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Phone Number</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExamCandidate->phone_number }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Created At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExamCandidate->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Updated At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExamCandidate->updated_at }}</dd>

        </dl>

    </div>
</div>

                </div>
            </div>
        </div>
    </section>
@endsection

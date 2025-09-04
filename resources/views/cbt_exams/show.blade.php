@extends('frontend.layouts.app')

@section('title', isset($cbtExam->title) ? $cbtExam->title : 'CBT Exam')

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($cbtExam->title) ? $cbtExam->title : 'CBT Exam' }}</h4>
        <div>
            <form method="POST" action="{!! route('cbt_exams.cbt_exam.destroy', $cbtExam->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('cbt_exams.cbt_exam.edit', $cbtExam->id ) }}" class="btn btn-primary" title="Edit CBT Exam">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="Delete CBT Exam" onclick="return confirm(&quot;Click Ok to delete CBT Exam.?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('cbt_exams.cbt_exam.index') }}" class="btn btn-primary" title="Show All CBT Exam">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('cbt_exams.cbt_exam.create') }}" class="btn btn-secondary" title="Create New CBT Exam">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">Title</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExam->title }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Start At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExam->start_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Duration In Minutes</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExam->duration_in_minutes }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Creator User</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($cbtExam->creatorUser)->full_name }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Created At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExam->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Updated At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtExam->updated_at }}</dd>

        </dl>

    </div>
</div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    @php $cbtExamCandidates = $cbtExam->cbtExamCandidates; @endphp
                    @include('cbt_exam_candidates.list', ['no_pagination' => true])
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush

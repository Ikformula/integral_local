@extends('frontend.layouts.app')

@section('title', 'Add new CBT Exam Candidate')

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .select2 {
            width: 100%;
        }
    </style>
@endpush

@section('content')

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
    <div class="card text-bg-theme">

         <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">Create New CBT Exam Candidate</h4>
{{--            <div>--}}
{{--                <a href="{{ route('cbt_exam_candidates.cbt_exam_candidate.index') }}" class="btn btn-primary" title="Show All CBT Exam Candidate">--}}
{{--                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>--}}
{{--                </a>--}}
{{--            </div>--}}
        </div>


        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="list-unstyled mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" class="needs-validation" novalidate action="{{ route('cbt_exam_candidates.cbt_exam_candidate.store') }}" accept-charset="UTF-8" id="create_cbt_exam_candidate_form" name="create_cbt_exam_candidate_form" >
            {{ csrf_field() }}
            @include ('cbt_exam_candidates.form', [
                                        'cbtExamCandidate' => null,
                                      ])

                <div class="col-lg-10 col-xl-9 offset-lg-2 offset-xl-3">
                    <input class="btn btn-primary" type="submit" value="Add">
                </div>
            </form>

        </div>
    </div>

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
    <script>
        const staff_members = @json($StaffMembers);
        $('#staff_ara_id').change(function(){
            const selected_ara_id = this.value;
            const staff = staff_members.find(({staff_ara_id}) => staff_ara_id == selected_ara_id);
            $('#surname').val(staff.surname);
            $('#other_names').val(staff.other_names);
            $('#gender').val(staff.gender);
            $('#state').val(staff.state);
            $('#email').val(staff.email);
        });
    </script>
@endpush

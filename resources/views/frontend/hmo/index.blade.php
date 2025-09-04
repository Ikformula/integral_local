@extends('frontend.layouts.app')

@section('title', 'Arik HMO')

@push('after-styles')
    @include('includes.partials._datatables-css')
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Staff HMO Data</div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-sm" id="staff-members-data">
                                            <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Surname</th>
                                                <th>Other Names</th>
                                                <th>ARA ID</th>
                                                <th>Email</th>
                                                <th>Department</th>
                                                <th>Job Title</th>
                                                <th>Unit</th>
                                                <th>Paypoint</th>
                                                <th>Grade</th>
                                                <th>Employment Status</th>
                                                <th>Staff Cadre</th>
                                                <th>Staff Category</th>
                                                <th>Gender</th>
                                                <th>Years of Service</th>
                                                <th>Region</th>
                                                <th>Marital Status</th>
                                                <th>View</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($staff_members as $staff_member)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $staff_member->surname ?? ''}}</td>
                                                    <td>{{ $staff_member->other_names ?? '' }}</td>
                                                    <td>ARA{{ $staff_member->staff_ara_id ?? '' }}</td>
                                                    <td>{{ $staff_member->email ?? '' }}</td>
                                                    <td>{{ $staff_member->department->name ?? '' }}</td>
                                                    <td>{{ $staff_member->job_title ?? '' }}</td>
                                                    <td>{{ $staff_member->unit ?? '' }}</td>
                                                    <td>{{ $staff_member->paypoint ?? '' }}</td>
                                                    <td>{{ $staff_member->grade ?? '' }}</td>
                                                    <td>{{ $staff_member->current_employment_status ?? '' }}</td>
                                                    <td>{{ $staff_member->staff_cadre ?? '' }}</td>
                                                    <td>{{ $staff_member->staff_category ?? '' }}</td>
                                                    <td>{{ $staff_member->gender ?? '' }}</td>
                                                    <td>{{ $staff_member->years_of_service ?? '' }}</td>
                                                    <td>{{ $staff_member->region ?? '' }}</td>
                                                    <td>{{ $staff_member->marital_status ?? '' }}</td>
                                                    <td><a href="{{ route('frontend.hmo.show.staff_member', $staff_member->staff_ara_id) }}" class="btn btn-sm btn-primary">View
{{--                                                            <i class="far fa-eye"></i>--}}
                                                        </a></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                        </div><!--/. container-fluid -->


    </section>
@endsection


@push('after-scripts')
    @include('includes.partials._datatables-js')

    <script>
        $("#staff-members-data").DataTable({
            "responsive": false, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#staff-members-data_wrapper .col-md-6:eq(0)');
    </script>
@endpush

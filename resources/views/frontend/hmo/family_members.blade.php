@extends('frontend.layouts.app')

@section('title', 'HMO | Family Members')

@push('after-styles')
    @include('includes.partials._datatables-css')
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Staff Family Members</div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-sm" id="staff-members-data">
                                            <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Staff Name</th>
                                                <th>ARA ID</th>
                                                <th>Family Member</th>
                                                <th>Relationship</th>
                                                <th>Gender</th>
                                                <th>DOB</th>
                                                <th>View</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($family_members as $family_member)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $family_member->staff_member->name ?? ''}}</td>
                                                    <td>ARA{{ $family_member->staff_member->staff_ara_id ?? '' }}</td>
                                                    <td>{{ $family_member->name ?? '' }}</td>
                                                    <td>{{ $family_member->relationship ?? '' }}</td>
                                                    <td>{{ $family_member->gender ?? '' }}</td>
                                                    <td>{{ $family_member->dob ?? '' }}</td>
                                                    <td><a href="{{ route('frontend.hmo.staff_member.familyMember', ['ara_number' => $family_member->staff_member->staff_ara_id, 'family_member' => $family_member->id]) }}" class="btn btn-sm btn-primary">View <i class="far fa-eye"></i> </a> </td>
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
            "responsive": true, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 400,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#staff-members-data_wrapper .col-md-6:eq(0)');
    </script>
@endpush

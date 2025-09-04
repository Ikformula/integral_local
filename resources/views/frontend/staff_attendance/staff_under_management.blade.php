@extends('frontend.layouts.app')

@section('title', 'Staff Under My Management' )

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive px-0 pb-0">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Staff</th>
                                    <th>ARA ID</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($staff_members as $staff_member)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $staff_member->name }}</td>
                                        <td>{{ $staff_member->staff_id }}</td>
                                        <td><a href="{{ route('frontend.attendance.create.manager.authorization') }}?staff_ara_id={{  $staff_member->staff_ara_id }}" class="btn bg-navy">Add Authorization</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

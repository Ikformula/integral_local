@extends('frontend.layouts.app')

@section('title', 'Staff Absence/Lateness Authorizations' )

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                    <div class="card-header">
                        <a href="{{ route('frontend.attendance.create.manager.authorization') }}" class="btn bg-navy">Add Authorization</a>
                    </div>
                        <div class="card-body table-responsive px-0 pb-0">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Staff</th>
                                    <th>Duration</th>
{{--                                    <th>Action</th>--}}
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($authorizations as $authorization)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $authorization->staff->name }}</td>
                                        <td>{{ $authorization->start_date->toDateString() }} - {{ $authorization->is_indefinite ? 'Indefinite' : $authorization->end_date->toDateString() }}</td>
{{--                                        <td></td>--}}
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

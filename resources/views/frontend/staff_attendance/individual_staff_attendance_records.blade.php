@extends('frontend.layouts.app')

@section('title', 'Staff Attendance Record' )

@push('after-styles')
    @include('includes.partials._datatables-css')
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                    <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $staff->name }} - {{ $staff->job_title }}
                            </h3>
                        </div>
{{--                        <div class="card-body">--}}
{{--                            @include('frontend.staff_attendance.partials._date_range_filter_form', ['staff_ara_id' => $staff->staff_ara_id])--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-striped" id="attendance-table">
                            <thead>
                            <tr>
                                <th>Dates</th>
                                <th>Resumed At</th>
                                <th>Closed At</th>
                                <th>Total Hours</th>
                            </tr>
                            </thead>

                            <tbody>
                                @foreach($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance['date'] }} <span class="badge badge-{{ $attendance['weekday'] != 'Saturday' && $attendance['weekday'] != 'Sunday' ? 'primary' : 'secondary' }}">{{ $attendance['weekday'] }}</span></td>
                                        <td>{{ $attendance['resumed'] }}</td>
                                        <td>{{ $attendance['closed'] }}</td>
                                        <td>{{ $attendance['hours'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    @include('includes.partials._datatables-js')

    <script>
        $("#attendance-table").DataTable({
            "responsive": false, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#attendance-table_wrapper .col-md-6:eq(0)');
    </script>
@endpush

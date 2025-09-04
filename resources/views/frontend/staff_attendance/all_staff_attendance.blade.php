@extends('frontend.layouts.app')

@section('title', 'Staff Attendance Records' )

@push('after-styles')
    @include('includes.partials._datatables-css')
    <link href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css" rel="stylesheet">
    <style>
        hr {
            margin-top: 3px;
            margin-bottom: 3px;
        }
    </style>
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @include('frontend.staff_attendance.partials._date_range_filter_form')
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <span class="badge" style="background-color: #FFADAD">On duty - no show</span>
                            <span class="badge" style="background-color: #FFFFFF">On duty - show</span>
                            <span class="badge" style="background-color: #FDFFB6">Remote - show</span>
                            <span class="badge" style="background-color: #a770d1">Manager Gave authorization</span>
                            <span class="badge" style="background-color: #CAFFBF">Weekend</span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            @if($auth_perm)
                                <div class="row mb-2">
                                    <div class="col">
                                        {{ $staffMembers->appends($params)->links() }}
                                    </div>
                                    <div class="col">
                                        <div class="btn-group float-right" role="group">
                                            <button class="search-btn btn btn-outline-dark" data-search-text="late">
                                                Latecoming
                                            </button>
                                            <button class="search-btn btn btn-outline-dark" data-search-text="absent">Absentees
                                            </button>
                                            <button class="search-btn btn btn-outline-dark" data-search-text="closed early">
                                                Closed Early
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            @endif

                            <table id="staff-attendance-table" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    {{--                                    <th>S/N</th>--}}
                                    <th>ARA ID</th>
                                    @if($auth_perm)
                                        <th>Name</th>
                                        <th>Lateness Notice</th>
                                        <th>Absenteeism Notice</th>
                                        <th>Job Title</th>
                                        <th>Department</th>
                                        <th>Shift Status</th>
                                    @endif
                                    @foreach($dates as $date)
                                        <th>{{ $date['week_day'] }} {{ $date['str'] }}</th>
                                    @endforeach
                                    <th>Lateness</th>
                                    <th>Absent</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($staffMembers as $staffMember)
                                    @php($staff_ara_id = $staffMember->staff_ara_id)
                                    <tr>
                                        {{--                                        <td>{{ $loop->iteration }}</td>--}}
                                        <td>{{ $staff_ara_id }}</td>
                                        @if($auth_perm)
                                            <td>{{ $staffMember->name }}</td>
                                        @endif
                                        @if(isset($attendances[$staff_ara_id]))
                                            @if($auth_perm)
                                                <td>
                                                    <button
                                                        class="btn bg-warning btn-app"
                                                        id="lateness-button-{{ $staff_ara_id }}"
                                                        onclick="sendEmail('{{ $staff_ara_id }}', 'lateness');">
                                                    <span
                                                        class="badge bg-teal"
                                                        id="lateness-badge-{{ $staff_ara_id }}"
                                                        @if(!$attendances[$staff_ara_id]['lateness']['emails_count']) style="display: none" @endif
                                                    >
                                                        {{ $attendances[$staff_ara_id]['lateness']['emails_count'] }}</span>
                                                        <i class="fa fa-envelope-open-text"></i>
                                                        <span
                                                            id="lateness-button-{{ $staff_ara_id }}-text">Send email</span>
                                                    </button>

                                                    <br><small
                                                        style="line-height: 8px;"><em>Last
                                                            sent:</em> <span
                                                            id="last-sent-lateness-{{ $staff_ara_id }}">{{ $attendances[$staff_ara_id]['lateness']['last'] }}</span>
                                                    </small>
                                                </td>

                                                <td>
                                                    <button
                                                        class="btn bg-info btn-app"
                                                        id="absence-button-{{ $staff_ara_id }}"
                                                        onclick="sendEmail('{{ $staff_ara_id }}', 'absence');">
                                                    <span
                                                        class="badge bg-teal"
                                                        id="absence-badge-{{ $staff_ara_id }}"
                                                        @if(!$attendances[$staff_ara_id]['absence']['emails_count']) style="display: none" @endif
                                                    >
                                                        {{ $attendances[$staff_ara_id]['absence']['emails_count'] }}</span>
                                                        <i class="fa fa-envelope-open-text"></i>
                                                        <span
                                                            id="absence-button-{{ $staff_ara_id }}-text">Send email</span>
                                                    </button>

                                                    <br><small
                                                        style="line-height: 8px;"><em>Last
                                                            sent:</em> <span
                                                            id="last-sent-absence-{{ $staff_ara_id }}">{{ $attendances[$staff_ara_id]['absence']['last'] }}</span>
                                                    </small>
                                                </td>
                                                <td>{{ $staffMember->job_title }}</td>
                                                <td>{{ $staffMember->department_name }}</td>
                                                <td>{{ $staffMember->shift_nonshift }}</td>
                                            @endif
                                        @php($stats[$staff_ara_id]['absent'] = 0)
                                        @php($stats[$staff_ara_id]['late'] = 0)
{{--                                        @php($stats[$staff_ara_id]['absent'] = 0)--}}
                                            @foreach($dates as $date)


                                                <td style="background-color: @if(isset($attendances[$staff_ara_id][$date['str']]['day_attendance']['manager_auth'])) #a770d1 @else {{$attendances[$staff_ara_id][$date['str']]['day_attendance']['day_schedule']['colour']}} @endif;

                                                @if($attendances[$staff_ara_id][$date['str']]['day_attendance']['closing_status'] == "closed early") border-bottom: 3px solid red; @endif"
                                                    @if(isset($attendances[$staff_ara_id][$date['str']]['day_attendance']['manager_auth']))
                                                        @php($manager_auth[$staff_ara_id][$date['str']] = $attendances[$staff_ara_id][$date['str']]['day_attendance']['manager_auth'])
                                                            title="{{ $manager_auth[$staff_ara_id][$date['str']]->manager->name }} gave authorization: {{ $manager_auth[$staff_ara_id][$date['str']]->reason }}"
                                                    @endif
                                                >
                                                    @if(!in_array($date['week_day'], ['Saturday', 'Sunday']) )
                                                        <small>@if($attendances[$staff_ara_id][$date['str']]['day_attendance']['day_schedule']['colour'] == '#F5F5F5') Working remotely @else {{$attendances[$staff_ara_id][$date['str']]['day_attendance']['status']}}


                                                            {{-- Cummulate stats for latensss and absence --}}
                                                            @if($attendances[$staff_ara_id][$date['str']]['day_attendance']['status'] == 'late')
                                                                @php($stats[$staff_ara_id]['late']++)
                                                            @endif

                                                            @if($attendances[$staff_ara_id][$date['str']]['day_attendance']['status'] == 'absent')
                                                                @php($stats[$staff_ara_id]['absent']++)
                                                            @endif

                                                            @endif</small>
                                                        <br>
                                                    @endif


                                                    <span
                                                        class="text-black-60">{{$attendances[$staff_ara_id][$date['str']]['day_attendance']['resumed']}}</span>
                                                    <br>

                                                    @if(!in_array($date['week_day'], ['Saturday', 'Sunday']) )
                                                        <small>{{$attendances[$staff_ara_id][$date['str']]['day_attendance']['closing_status']}}</small>
                                                    @endif

                                                    @if($attendances[$staff_ara_id][$date['str']]['day_attendance']['closing_status'] != "wasn't clocked out")
                                                        <br>

                                                        <span
                                                            class="text-maroon">{{$attendances[$staff_ara_id][$date['str']]['day_attendance']['closed']}} </span>
                                                    @endif
                                                    @if($attendances[$staff_ara_id][$date['str']]['day_attendance']['day_schedule']['colour'] != '#F5F5F5')
                                                        <hr class="border-secondary"> {{$attendances[$staff_ara_id][$date['str']]['day_attendance']['hours']}}
                                                        hours
                                                    @endif
                                                </td>
                                            @endforeach
                                        @endif

                                        <td>{{ $stats[$staff_ara_id]['late'] }}</td>
                                        <td>{{ $stats[$staff_ara_id]['absent'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                                {{ $staffMembers->appends($params)->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    @include('includes.partials._datatables-js')

    <script src="{{ asset('adminlte3.2/plugins/pace-progress/pace.min.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>


    <script>

        var table = $("#staff-attendance-table").DataTable({
            // "responsive": false, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            "responsive": false,
            "lengthChange": false,
            "autoWidth": true,
            paging: false,
            scrollY: 465,
            scrollX: true,
            scrollCollapse: true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", @if($auth_perm)"colvis"@endif],
            @if($auth_perm)
            fixedColumns: {
                left: 4,
            }
            @endif
        }).buttons().container().appendTo('#staff-attendance-table_wrapper .col-md-6:eq(0)');

        $('.search-btn').on('click', function () {
            var searchText = $(this).data('search-text');

            // Find the first input field inside the div with id="staff-attendance-table_filter"
            var searchInput = $('#staff-attendance-table_filter input[type="search"]').first();

            // Set the search value
            searchInput.val(searchText);

            // Trigger the search on the DataTable
            var table = $("#staff-attendance-table").DataTable();
            table.search(searchText).draw();
        });

        function sendEmail(staff_ara_id, email_type) {
            document.getElementById(email_type + '-button-' + staff_ara_id + '-text').innerHTML = 'Sending...';

            fetch('{{ route('frontend.attendance.send.attendance.email') }}?staff_ara_id=' + staff_ara_id + '&email_type=' + email_type)
                .then(function (response) {
                    return response.json();
                }).then(function (data) {
                if(data.message == 'Email sent'){
// Modify the HTML content of the element with ID: `${email_type}-badge-${staff_ara_id}`
                    document.getElementById(email_type + '-badge-' + staff_ara_id).innerHTML = data.emails_count;

// Show the element with ID: `${email_type}badge-${staff_ara_id}`
                    document.getElementById(email_type + '-badge-' + staff_ara_id).style.display = 'block'; // or 'inline-block', depending on the element type

// Modify the HTML content of the element with ID: `last-sent${email_type}badge-${staff_ara_id}`
                    document.getElementById('last-sent-' + email_type + '-'  + staff_ara_id).innerHTML = data.last_sent;
                    document.getElementById(email_type + '-button-' + staff_ara_id + '-text').innerHTML = 'Email sent';
                    showInstantToast(data.message, 'success');

                }else{
                    showInstantToast('Message not sent: ' + data.message, 'error');
                    document.getElementById(email_type + '-button-' + staff_ara_id + '-text').innerHTML = 'Send email';
                }
            });
        }
    </script>


@endpush

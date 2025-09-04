@extends('frontend.layouts.app')

@section('title', 'Staff Attendance Records' )

@push('after-styles')
    @include('includes.partials._datatables-css')
    <link href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css" rel="stylesheet">
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
                            <span class="badge" style="background-color: #CAFFBF">Weekend</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="attendance-table">
                                    <thead>
                                    <tr id="jax-head">
                                    </tr>
                                    </thead>

                                    <tbody id="jax-table">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{ $staff_members->appends(request()->except('page'))->links() }}
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
        let staff_members_json = {!!  $staff_members_json  !!};

        $(document).ready(function () {
            // attendance-table
            let i;
            let sn = 1;
            let iLength = staff_members_json.length;
            for (i = 0; i < iLength; i++) {
                getStaffAttendance(staff_members_json[i], i);
            }

            // if(sn >= iLength){
            //     setDataTable();
            // }

            function getStaffAttendance(staff_member_data, i) {
                let staff_ara_id = staff_member_data.staff_ara_id;
                fetch('{{ route('getIndividualStaffAttendance') }}?staff_ara_id=' + staff_member_data.staff_ara_id + '&from_date={{ $from_date }}&to_date={{ $to_date }}').then(function (response) {
                    // The API call was successful!
                    return response.json();
                }).then(function (data) {
                    // console.log(data);
                    // This is the JSON from our response
                    if (i == 0) {
                        // add table header row
                        let dates = `<th>ARA ID</th>
                            <th>Staff Member</th>
                            <th>Department Name</th>
                            <th>Email Notice</th>`;
                        data.attendances.forEach(function (day_attendance) {
                            dates += `<th> ${day_attendance.weekday} ${day_attendance.date}</th>`;
                        });

                        $('#jax-head').append(dates);
                    }

                    let attendances = '';
                    data.attendances.forEach(function (day_attendance) {
                        // console.log(day_attendance);
                        attendances += `<td style="background-color: ${day_attendance.day_schedule.colour}"> <span class="text-black-60">${day_attendance.resumed}</span> <br> <span class="text-maroon">${day_attendance.closed} </span> <br> ${day_attendance.hours} hours </td>`;
                    });
                    let surname = staff_member_data.surname == null ? '' : staff_member_data.surname;
                    let trow = `
                    <tr>
                        <td>${staff_member_data.staff_ara_id}</td>
                        <td>${surname} ${staff_member_data.other_names}</td>
                        <td>${staff_member_data.department_name}</td>
                        <td><a href="{{ route('frontend.attendance.send.attendance.email') }}?staff_ara_id=${staff_member_data.staff_ara_id}" class="btn btn-warning" target="_blank">Send Lateness Email</a><br><small class="text-small">Last email sent: ${data.last_email_time}</small></td>
                          ${attendances}
                    </tr>
                    `;
                    sn++;
                    if (sn == (iLength - 1)) {
                        setDataTable();
                    }

                    $('#jax-table').append(trow);
                }).catch(function (err) {
                    // There was an error
                    console.warn('Something went wrong.', err);
                });
            }
        });

        function setDataTable() {
            // alert('datatable');
            $("#attendance-table").DataTable({
                // "responsive": false, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465,
                // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                "responsive": false, "lengthChange": false, "autoWidth": true, paging: false, scrollY: 465, scrollX: true, scrollCollapse: true, "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"], fixedColumns: {
                    left: 3,
                }
            }).buttons().container().appendTo('#attendance-table_wrapper .col-md-6:eq(0)');
        }

        function notifyLateComer($staff_ara_id){

        }
    </script>


@endpush

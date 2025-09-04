@extends('frontend.layouts.app')

@section('title', 'Staff Attendance Records' )

@push('after-styles')
    @include('includes.partials._datatables-css')
    <link href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Filter Form Card -->
        <div class="card mb-3">
            <div class="card-header">Filter Weekly Summaries</div>
            <div class="card-body">
                <form method="GET" action="{{ route('frontend.attendance.staff_attendance.weekly_summaries') }}">
                    <div class="form-group">
                        <label for="week_range">Select Weeks (Up to 4):</label>
                        <select name="week_range[]" id="week_range" class="form-control" multiple>
                            @foreach($availableWeeks as $weekId => $weekNumber)
                                <option value="{{ $weekId }}" {{ in_array($weekId, $weekRangeIds) ? 'selected' : '' }}>
                                    Week {{ $weekNumber }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>
        </div>

        <!-- Weekly Summaries Table Card -->
        <div class="card">
            <div class="card-header">Staff Weekly Summaries</div>
            <div class="card-body">
                @if($weeklySummaries->isEmpty())
                    <p>No summaries available for the selected weeks.</p>
                @else
                    @include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'weekly-summaries'] )
                    <div class=" table-responsive" style="height: 300px;">
                        <table class="table table-bordered table-hover table-head-fixedd text-nowrap table-striped staff-weekly-summaries" id="weekly-summaries">
                            @php
                                $shaded_hd_bg = '#eff1f2';
                                $shaded_bg = '#f4f7f8';
                                $shaded_light = '#ffffff';

 @endphp
                            <thead>
                            <tr>
                                <th class="freeze-column"></th>
                                <th class="freeze-column-2 shadow"></th>
                                <th class="freeze-column"></th>
                                <th class="freeze-column"></th>
                                @foreach($last4Weeks as $week)
                                    <th class="week-header" style="background-color: @if($loop->even) {{ $shaded_bg }} @else {{ $shaded_light }} @endif" >Week {{ $week->week_number }}</th>
                                    <th class="week-header" style="background-color: @if($loop->even) {{ $shaded_bg }} @else {{ $shaded_light }} @endif"></th>
                                    <th class="week-header" style="background-color: @if($loop->even) {{ $shaded_bg }} @else {{ $shaded_light }} @endif" >Total Late {{ $total_late[$week->id] }}</th>
                                    <th class="week-header" style="background-color: @if($loop->even) {{ $shaded_bg }} @else {{ $shaded_light }} @endif" >Absent {{ $total_absent[$week->id] }}</th>
                                    <th class="week-header" style="background-color: @if($loop->even) {{ $shaded_bg }} @else {{ $shaded_light }} @endif"></th>
                                @endforeach
                            </tr>
                            <tr>
                                <th class="">Staff ID</th>
                                <th class="shadow">Staff Name</th>
                                <th class="">Department</th>
                                <th class="">Shift Status</th>
                                @foreach($last4Weeks as $week)
                                    <th style="background-color: @if($loop->even) {{ $shaded_hd_bg }} @else {{ $shaded_light }} @endif" >Late</th>
                                    <th style="background-color: @if($loop->even) {{ $shaded_hd_bg }} @else {{ $shaded_light }} @endif" >Absent</th>
                                    <th style="background-color: @if($loop->even) {{ $shaded_hd_bg }} @else {{ $shaded_light }} @endif" >Hours</th>
                                    <th style="background-color: @if($loop->even) {{ $shaded_hd_bg }} @else {{ $shaded_light }} @endif" >Early Leaving</th>
                                    <th style="background-color: @if($loop->even) {{ $shaded_hd_bg }} @else {{ $shaded_light }} @endif" >Remarks</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($staffMembers as $staff)
                                <tr>
                                    <td class="">{{ $staff->staff_id }}</td>
                                    <td class="-2 shadow">{{ $staff->surname }} {{ $staff->other_names }}</td>
                                    <td class="">{{ $staff->department_name }}</td>
                                    <td class="">{{ $staff->shift_nonshift }}</td>
                                    @foreach($last4Weeks as $week)
                                        @php
                                            $summary = $weeklySummaries[$staff->staff_ara_id]->where('week_range_id', $week->id)->first() ?? null;
                                        @endphp
                                        <td @if($loop->even) style="background-color: {{ $shaded_bg }}" @endif >{{ $summary ? $summary->late : '' }}</td>
                                        <td @if($loop->even) style="background-color: {{ $shaded_bg }}" @endif >{{ $summary ? $summary->absent : '' }}</td>
                                        <td @if($loop->even) style="background-color: {{ $shaded_bg }}" @endif >{{ $summary ? $summary->total_work_hours : '' }}</td>
                                        <td @if($loop->even) style="background-color: {{ $shaded_bg }}" @endif >{{ $summary ? $summary->early_leaving : '' }}</td>
                                        <td @if($loop->even) style="background-color: {{ $shaded_bg }}" @endif >{{ $summary ? $summary->remarks_and_reasons : '' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>

                @endif
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    @include('includes.partials._datatables-js')

    <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>


    <script>

        var table = $("#weekly-summaries").DataTable({
            // "responsive": false, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            "responsive": false,
            "lengthChange": false,
            "autoWidth": true,
            paging: false,
            scrollY: 465,
            scrollX: true,
            scrollCollapse: true,
            "buttons": ["copy", "csv", "excel", "print"],
            fixedColumns: {
                left: 4,
            }
        }).buttons().container().appendTo('#weekly-summaries_wrapper .col-md-6:eq(0)');

    </script>
    <script src="{{ asset('js/html-table-xlsx.js') }}"></script>
@endpush

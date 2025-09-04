@extends('frontend.layouts.app')

@section('title', 'Attendance Summaries ' . $formattedStartDate .' to '.$formattedEndDate)

@push('after-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('content')
    <div class="container mt-4">

        {{-- Filter Card --}}
        <div class="card mb-4">
            <div class="card-header">Filter by Date and Shift Type</div>
            <div class="card-body">
                <form method="GET" action="{{ route('frontend.attendance.staff.attendance.summaries') }}" class="form-inline flex-wrap">
                    <div class="form-group mr-3 mb-2">
                        <label for="start_date" class="mr-2">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date', $startDate) }}" class="form-control">
                    </div>
                    <div class="form-group mr-3 mb-2">
                        <label for="end_date" class="mr-2">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date', $endDate) }}" class="form-control">
                    </div>
                    <div class="form-group mr-3 mb-2">
                        <label for="shift_nonshift" class="mr-2">Shift Type</label>
                        <select name="shift_nonshift" id="shift_nonshift" class="form-control">
                            <option value="NON-SHIFT" {{ $shiftType == 'NON-SHIFT' ? 'selected' : '' }}>NON-SHIFT</option>
                            <option value="SHIFT" {{ $shiftType == 'SHIFT' ? 'selected' : '' }}>SHIFT</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Filter</button>
                </form>

                <div class="mt-2">
                    <strong>Quick Filters:</strong>
                    @for ($i = 1; $i <= now()->month; $i++)
                        <a href="{{ route('frontend.attendance.staff.attendance.summaries', ['start_date' => now()->startOfMonth()->month($i)->format('Y-m-d'), 'end_date' => now()->startOfMonth()->month($i)->endOfMonth()->format('Y-m-d'), 'shift_nonshift' => $shiftType]) }}"
                           class="btn btn-sm btn-outline-secondary mt-1">
                            {{ now()->month($i)->format('F') }}
                        </a>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Attendance Summary Table --}}
        <div class="card">
            <div class="card-header">
                Attendance Summary ({{ $formattedStartDate }} to {{ $formattedEndDate }}) - <strong>{{ $shiftType }}</strong> Staff
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="thead-light">
                    <tr>
                        <th>Department</th>
                        <th>Staff ARA ID</th>
                        <th>Name</th>
{{--                        <th>Email</th>--}}
                        <th>On-Time</th>
                        <th>Late</th>
                        <th>Absent</th>
                        <th>Total Hours</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $groupedByDepartment = $staffSummaries->groupBy('department_name');
                        $firmTotal = ['on_time' => 0, 'late' => 0, 'absent' => 0, 'hours' => 0];
                    @endphp

                    @foreach ($groupedByDepartment as $department => $entries)
                        @php
                            $deptTotal = ['on_time' => 0, 'late' => 0, 'absent' => 0, 'hours' => 0];
                        @endphp

                        @foreach ($entries as $entry)
                            <tr @if($entry['on_time'] + $entry['late'] + $entry['absent'] === 0) class="text-muted" @endif>

                            <td>{{ $department }}</td>
                                <td>{{ $entry['staff_ara_id'] }}</td>
                                <td>{{ $entry['surname'] }} {{ $entry['other_names'] }}</td>
{{--                                <td>{{ $entry['email'] }}</td>--}}
                                <td>{{ $entry['on_time'] }}</td>
                                <td>{{ $entry['late'] }}</td>
                                <td>{{ $entry['absent'] }}</td>
                                <td>{{ $entry['hours'] }}</td>
                            </tr>

                            @foreach (['on_time', 'late', 'absent', 'hours'] as $key)
                                @php
                                    $deptTotal[$key] += $entry[$key];
                                    $firmTotal[$key] += $entry[$key];
                                @endphp
                            @endforeach
                        @endforeach

                        {{-- Department Subtotal --}}
                        <tr class="table-secondary font-weight-bold">
                            <td>{{ $department }} Total</td>
                            <td></td>
                            <td></td>
{{--                            <td></td>--}}
                            <td>{{ $deptTotal['on_time'] }}</td>
                            <td>{{ $deptTotal['late'] }}</td>
                            <td>{{ $deptTotal['absent'] }}</td>
                            <td>{{ $deptTotal['hours'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>

                    {{-- Firm-wide Total --}}
                    <tfoot>
                    <tr class="table-dark font-weight-bold">
                        <td>Firm Total</td>
                        <td></td>
                        <td></td>
{{--                        <td></td>--}}
                        <td>{{ $firmTotal['on_time'] }}</td>
                        <td>{{ $firmTotal['late'] }}</td>
                        <td>{{ $firmTotal['absent'] }}</td>
                        <td>{{ $firmTotal['hours'] }}</td>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>

    </div>
@endsection

@push('after-scripts')
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/dataTables.searchBuilder.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/searchBuilder.dataTables.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>


    <script>
        $(document).ready(function () {
            var table = new DataTable('.table', {
                "paging": false,
                scrollY: 465,
                layout: {
                    top: {
                        searchBuilder: {
                            // columns: [6],
                            @if(isset($_GET['days_left']))
                            preDefined: {
                                {{--criteria: [--}}
                                {{--    {--}}
                                {{--        data: 'Days Left to End',--}}
                                {{--        condition: '=',--}}
                                {{--        value: [{{ $_GET['days_left'] }}]--}}
                                {{--    }--}}
                                {{--]--}}
                            }
                            @endif
                        }
                    },
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                }
            });
        });
    </script>
@endpush

@extends('frontend.layouts.app')

@section('title',  $group->name.' Tickets/Issues')

@push('after-styles')
    @include('includes.partials._datatables-css')
    <style>
        .container-fluid {
            zoom: 100%;
        }

        .zoom-80 {
            zoom: 80%;
        }
    </style>
@endpush


@section('content')

    @if($user_is_service_now_agent)
        <table id="reports-hidden" style="display: none;">
            <tbody>
            <tr>
                <td>{{ $group->name }} Tickets/Issues Stats For</td>
            </tr>
            <tr>
                <td>{{ substr($from_date, 0, 10) }}</td>
                <td>to</td>
                <td>{{ substr($to_date, 0, 10) }}</td>
            </tr>

            <tr>
                <td></td>
            </tr>

            <tr>
                <td>Total</td>
                <td>{{ $tickets->count() }}</td>
            </tr>
            @foreach($form_values['statuses'] as $status)
                <tr>
                    <td>{{ ucfirst($status) }}</td>
                    <th id="stats_{{ $status }}">@if($stats[$status]){{ $stats[$status] }} @else 0 @endif</th>
                </tr>
            @endforeach
            <tr>
                <td></td>
            </tr>

            <tr>
                <th>Ticket Types</th>
                @foreach($form_values['statuses'] as $status)
                    <th>{{ ucfirst($status) }}</th>
                @endforeach
                <th>Total</th>
            </tr>

            @foreach($tickets->unique('ticketType.title') as $ticket)
                <tr>
                    <td>{{ $ticket->ticketType->title }}</td>
                    @php
                        $total = 0;
                    @endphp
                    @foreach($form_values['statuses'] as $status)
                        <td>
                            @php
                                $count = $tickets->where('ticketType.title', $ticket->ticketType->title)->where('status', $status)->count();
                                $total += $count;
                            @endphp
                            {{ $count }}
                        </td>
                    @endforeach
                    <td>{{ $total }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <p>{{ $group->description }}</p>
                </div>
            </div>
            @if($user_is_service_now_agent)
                <div class="row">
                    <div class="col">
                        <div class="card arik-card collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title">Charts</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card card-info">
                                            <div class="card-body">
                                                <h3 class="card-title">Ticket Assignments Chart</h3>

                                                <canvas id="pieChart"
                                                        style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card card-info">
                                            <div class="card-body">
                                                <h3 class="card-title">Statuses</h3>
                                                <canvas id="statusesDonutChart"
                                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="card card-warning">
                                            <div class="card-body">
                                                <h3 class="card-title">Origin Types</h3>
                                                <canvas id="origin_typesChart"
                                                        style="min-height: 250px; height: 400px; max-height: 400px; max-width: 100%;"></canvas>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                    <div class="col-md-5 ">
                                        <div class="card card-warning">
                                            <div class="card-header">
                                                <span
                                                    class="">IT Tickets/Issues Stats For <strong>{{ $from_date->toDayDateTimeString() }}</strong> to <strong>{{ $to_date->toDayDateTimeString() }}</strong></span>
                                            </div>
                                            <div class="card-body p-0">
                                                <table class="table table-bordered table-sm">
                                                    <tbody>
                                                    <tr>
                                                        <td>Total</td>
                                                        <td>{{ $tickets->count() }}</td>
                                                    </tr>
                                                    @foreach($form_values['statuses'] as $status)
                                                        <tr>
                                                            <td>{{ ucfirst($status) }}</td>
                                                            <th id="stats_{{ $status }}">@if($stats[$status]){{ $stats[$status] }} @else
                                                                    0 @endif</th>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>

                                                <table class="table table-bordered table-sm">
                                                    <tbody>
                                                    <tr>
                                                        <th>Ticket Types</th>
                                                        @foreach($form_values['statuses'] as $status)
                                                            <th>{{ ucfirst($status) }}</th>
                                                        @endforeach
                                                        <th>Total</th>
                                                    </tr>

                                                    @foreach($tickets->unique('ticketType.title') as $ticket)
                                                        <tr>
                                                            <td>{{ $ticket->ticketType->title }}</td>
                                                            @php
                                                                $total = 0;
                                                            @endphp
                                                            @foreach($form_values['statuses'] as $status)
                                                                <td>
                                                                    @php
                                                                        $count = $tickets->where('ticketType.title', $ticket->ticketType->title)->where('status', $status)->count();
                                                                        $total += $count;
                                                                    @endphp
                                                                    {{ $count }}
                                                                </td>
                                                            @endforeach
                                                            <td>{{ $total }}</td>
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
                    </div>
                </div>
        @endif

        <div class="row zoom-80">
            <div class="col">
                <div class="card arik-card">
                    <div class="card-header">
                        <h3 class="card-title">Filters</h3>
                        <div class="card-tools align-content-end">
                            <a href="{{ route('frontend.service_now.tickets.create', $group) }}"
                               class="btn btn-outline-maroon bg-gradient-navy pr-3"><i class="far fa-plus"></i> Add
                                Ticket </a>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <form method="GET">
                            <div class="row ">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="date" min="{{ substr($earliest_date, 0, 10) }}" name="from_date"
                                               value="{{ substr($from_date, 0, 10) }}" class="form-control">
                                        <label>From Date (Earliest: {{ substr($earliest_date, 0, 10) }})</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="date" name="to_date" max="{{ now()->toDateString() }}"
                                               value="{{ substr($to_date, 0, 10) }}" class="form-control">
                                        <label>To Date</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn bg-cyan">Filter</button>
                                </div>

                                @if($user_is_service_now_agent)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary btn-block"
                                                    onclick="exportToCSV()"><i class="fa fa-file-export"></i> Export
                                                Report
                                            </button>
                                            <label>Export report to CSV for this date range</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    @foreach($form_values['priorities'] as $priority)
                                        <label class="btn btn-outline-secondary btn-sm bg-gradient-navy">
                                            <input type="radio" name="filter_priority_radio" value="{{ $priority }}"
                                                   id="{{ $priority }}" autocomplete="off"> {{ ucfirst($priority) }}

                                            @if($stats[$priority])<span
                                                class="badge bg-warning">{{ $stats[$priority] }}</span>@endif
                                        </label>
                                    @endforeach
                                </div>
                                <p class=""><small>Priority</small></p>
                            </div>
                            <div class="col-md-6">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    @foreach($form_values['statuses'] as $status)
                                        <label class="btn btn-outline-secondary btn-sm bg-gradient-maroon">
                                            <input type="radio" name="filter_status_radio" value="{{ $status }}"
                                                   id="{{ $status }}" autocomplete="off"> {{ ucfirst($status) }}
                                            @if($stats[$status])<span
                                                class="badge bg-white">{{ $stats[$status] }}</span>@endif
                                        </label>
                                    @endforeach
                                </div>
                                <p class=""><small>Status</small></p>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-outline-secondary bg-gradient-light btn-sm"
                                        id="reset-button"><i class="fas fa-times" id="reset-button-fa"></i> Reset
                                    Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row zoom-80">
            <div class="col">
                <div class="card arik-card">

                    {{--                        <div class="card-header">--}}
                    {{--                                --}}
                    {{--                        </div>--}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tickets">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Issue</th>
                                    <th>Concerned Staff</th>
                                    <th>Type</th>
                                    <th>Assigned to</th>
                                    <th>Group</th>
                                    <th>Priority</th>
                                    <th>Aging</th>
                                    <th>Last Update</th>
                                    <th>Created</th>
                                    <th>Status</th>
                                    {{--                                       <th>Action</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @php $to_collect = [] @endphp
                                @foreach($tickets as $ticket)

                                    <tr onclick="location.href = '{{ route('frontend.service_now.tickets.show', $ticket) }}'"
                                        class="@if(in_array($ticket->status, ['closed', 'resolved']))bg-gradient-secondary @elseif(isset($ticket->agent) && $ticket->agent_user_id == $logged_in_user->id) bg-gradient-lightblue @endif ">
                                        <td>{{ $ticket->ticket_id_number }}</td>
                                        <td>
                                            <a href="{{ route('frontend.service_now.tickets.show', $ticket) }}"
                                               class="link">{{ $ticket->title }}</a>
                                        </td>

                                        @php

                                            $to_collect['concernedStaff'][$loop->iteration] = is_object($ticket->concernedStaff) ? $ticket->concernedStaff->name .' ('.$ticket->concerned_staff_ara_id.')'.' - '.$ticket->concernedStaff->department_name : 'n/a';
                                            $to_collect['type'][$loop->iteration] = $ticket->ticketType->title;
                                            $to_collect['assignedTo'][$loop->iteration] = isset($ticket->agent) ? $ticket->agent->full_name : '';
                                            $to_collect['group'][$loop->iteration] = $ticket->group->name;
                                            $to_collect['priority'][$loop->iteration] = '<i class="fas fa-'.$ticket->priorityUI().'"></i> '.ucfirst($ticket->priority);
                                            $ticket_type = $ticket->ticketType;
                                            $to_collect['aging'][$loop->iteration] = '<span class="badge badge-'.$ticket->agingColour().'">'.$ticket->created_at->diffForHumans().' '.$ticket->agingData().'</span>';
                                            $to_collect['lastUpdate'][$loop->iteration] = $ticket->updated_at->diffForHumans();
                                            $to_collect['status'][$loop->iteration] = $ticket->status;

                                        @endphp

                                        <td>{{ $to_collect['concernedStaff'][$loop->iteration] }}</td>
                                        <td>{{ $to_collect['type'][$loop->iteration] }}</td>
                                        <td>{{ $to_collect['assignedTo'][$loop->iteration] }}</td>
                                        <td>{{ $to_collect['group'][$loop->iteration] }}</td>
                                        <td>{!! $to_collect['priority'][$loop->iteration] !!}</td>
                                        <td>{!! $to_collect['aging'][$loop->iteration] !!}</td>
                                        <td>{{ $to_collect['lastUpdate'][$loop->iteration] }}</td>
                                        <td>{{ $ticket->created_at->toDateTimeString() }}</td>
                                        <td>{{ $to_collect['status'][$loop->iteration] }}</td>
                                    </tr>

                                @endforeach

                                </tbody>

                                {{--                                           <td><a href="{{ route('frontend.service_now.tickets.show', $ticket) }}" class="btn btn-info btn-xs">View</a></td>--}}
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
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>--}}
    {{--    <script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>--}}

    <script>
        $(document).ready(function () {

            @php
                $unique = [
                'concernedStaff' => isset($to_collect['concernedStaff']) ? array_unique($to_collect['concernedStaff']) : [],
                'type' => isset($to_collect['type']) ? array_unique($to_collect['type']) : [],
                'assignedTo' => isset($to_collect['assignedTo']) ? array_unique($to_collect['assignedTo']) : [],
                'group' => isset($to_collect['group']) ? array_unique($to_collect['group']) : [],
                'priority' => isset($to_collect['priority']) ? array_unique($to_collect['priority']) : [],
                'aging' => isset($to_collect['aging']) ? array_unique($to_collect['aging']) : [],
                'lastUpdate' => isset($to_collect['lastUpdate']) ? array_unique($to_collect['lastUpdate']) : [],
                'status' => isset($to_collect['status']) ? array_unique($to_collect['status']) : [],
            ];

            @endphp

            var uniqueData = {!! json_encode($unique) !!};

// dataTable
            // Setup - add a text input to each footer cell
            $('#tickets thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#tickets thead');

            var table = $('#tickets').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                paging: false,
                scrollY: 465,
                scrollX: true,
                scrollCollapse: true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                order: [9, 'desc'],

                orderCellsTop: true,
                fixedHeader: true,
                initComplete: function () {
                    var api = this.api();

                    let th_count = 1;
                    const filtered_th = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];

// For each column
                    api.columns().eq(0).each(function (colIdx) {
                        if (filtered_th.includes(th_count)) {
                            var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
                            var title = $(cell).text();

                            // Get the corresponding column name
                            var columnName = "";
                            switch (th_count) {
                                case 3:
                                    columnName = 'concernedStaff';
                                    break;
                                case 4:
                                    columnName = 'type';
                                    break;
                                case 5:
                                    columnName = 'assignedTo';
                                    break;
                                case 6:
                                    columnName = 'group';
                                    break;
                                case 7:
                                    columnName = 'priority';
                                    break;
                                case 8:
                                    columnName = 'aging';
                                    break;
                                case 9:
                                    columnName = 'lastUpdate';
                                    break;
                                case 10:
                                    columnName = 'createdAt';
                                    break;
                                case 11:
                                    columnName = 'status';
                                    break;
                                default:
                                    columnName = 'none';
                            }


                            // Set the header cell content based on the column name
                            if (columnName !== 'none') {
                                var data_list = ``;
                                if (uniqueData.hasOwnProperty(columnName)) {
                                    // Loop through the values for the "concernedStaff" column
                                    for (let value in uniqueData[columnName]) {
                                        if (uniqueData[columnName].hasOwnProperty(value)) {
                                            // Append the content for each <option> element to the data_list string
                                            data_list += `<option value="${uniqueData[columnName][value]}"></option>`;
                                        }
                                    }
                                }
                                $(cell).html(`<input type="search" list="${columnName}List" id="${columnName}_input" class="form-control-sm" placeholder="${title}" />
                <datalist id="${columnName}List">
                ${data_list}
                </datalist>
            `);
                            } else {
                                $(cell).html('<input type="search" class="form-control-sm" placeholder="' + title + '" />');
                            }

                            // On every keypress in this input
                            $(
                                'input',
                                $('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                                .off('keyup change')
                                .on('change', function (e) {
                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != ''
                                                ? regexr.replace('{search}', '(((' + this.value + ')))')
                                                : '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();
                                })
                                .on('keyup', function (e) {
                                    e.stopPropagation();

                                    $(this).trigger('change');
                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        }
                        th_count++;
                    });
                },
            })
                .buttons().container().appendTo('#tickets_wrapper .col-md-6:eq(0)');
        });


        $('input[name=filter_priority_radio]').on('change', function () {
            var searchText = $(this).val();
            setColumnText(searchText, 6, 'priority');
        });

        $('input[name=filter_status_radio]').on('change', function () {
            var searchText = $(this).val();
            setColumnText(searchText, 10, 'status');
        });

        function setColumnText(searchText, columnNum, columnName) {
            $('#' + columnName + '_input').val(searchText);
            if (searchText.length) {
                $("#reset-button-fa").addClass("fa-spin");
            } else {
                $("#reset-button-fa").removeClass("fa-spin");
                $('.btn-group-toggle label').removeClass('active');
            }
            searchColumnValue(columnNum, searchText);
        }

        $('#reset-button').on('click', function () {
            var searchText = '';
            setColumnText(searchText, 6, 'priority');
            setColumnText(searchText, 10, 'status');
        });

        // Function to search for a value in a specific column
        function searchColumnValue(columnIndex, searchValue) {
            var table = $("#tickets").DataTable();
            table.column(columnIndex).search(searchValue).draw();
        }

        function exportToCSV() {
            var csv = [];
            var rows = document.querySelectorAll("#reports-hidden tr");

            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll("td, th");

                for (var j = 0; j < cols.length; j++) {
                    row.push(cols[j].innerText);
                }

                csv.push(row.join(","));
            }

            // Create a blob object representing the data as a CSV file
            var blob = new Blob([csv.join("\n")], {
                type: 'text/csv;charset=utf-8'
            });

            // Create a link element, set its href attribute to the blob object, and click it programmatically
            var a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'IT Issues Report For {{ substr($from_date, 0, 10) }} to {{ substr($to_date, 0, 10) }}.csv';
            a.click();
        }

    </script>
    @if($user_is_service_now_agent)
    <script src="{{ asset('adminlte3.2/plugins/chart.js/Chart.min.js') }}"></script>
    <script>

        @if(count($charts['assignees']))
        // PIE Chart
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
        @php $assignee_colours = generateDistinctPastelColors(sizeof($charts['assignees'])); @endphp
        var assigneeData = {
            labels: [
                @foreach($charts['assignees'] as $assignee)
                    '{{ $assignee['name'] }} - {{ $assignee['num_tickets'] }}',
                @endforeach
            ],
            datasets: [
                {
                    data: [@foreach($charts['assignees'] as $assignee){{ $assignee['num_tickets'] }},@endforeach],
                    backgroundColor: [@for($i = 1; $i <= count($charts['assignees']); $i++)'{{ $assignee_colours[$i - 1]}}',@endfor],
                }
            ]
        }
        var pieData = assigneeData;
        var pieOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        })
        @endif

        @if(sizeof($charts['statuses']))
        // Statuses Donut Chart
        var statusesDonutChartCanvas = $('#statusesDonutChart').get(0).getContext('2d');
        @php $status_colours = generateDistinctPastelColors(sizeof($charts['statuses'])); @endphp
        var statusData = {
            labels: [
                @foreach($charts['statuses'] as $status => $number)
                    '{{ $status }} - {{ $number }}',
                @endforeach
            ],
            datasets: [
                {
                    data: [@foreach($charts['statuses'] as $status => $number){{ $number }},@endforeach],
                    backgroundColor: [@for($i = 1; $i <= count($charts['statuses']); $i++)'{{ $status_colours[$i - 1]}}',@endfor],
                }
            ]
        }
        var statusesDonutData = statusData;
        var statusesDonutOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        //Create statusesDonut or doughnut chart
        // You can switch between statusesDonut and douhnut using the method below.
        new Chart(statusesDonutChartCanvas, {
            type: 'doughnut',
            data: statusesDonutData,
            options: statusesDonutOptions
        });
        @endif


        @if(sizeof($charts['origin_types']))
        // Get data from PHP array
        var origin_types = @json($charts['origin_types']);
        @php $origin_types_colours = generateDistinctPastelColors(sizeof($charts['origin_types'])); @endphp

        // Extract labels and values from the origin_types array
        var labels = Object.keys(origin_types);
        var values = Object.values(origin_types);

        // Create a new Chart instance
        var ctx = document.getElementById('origin_typesChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'origin_types',
                    data: values,
                    backgroundColor: [
                        @foreach($origin_types_colours as $colour)
                            '{{ $colour }}',
                        @endforeach
                    ],
                    borderColor: [
                        @foreach($origin_types_colours as $colour)
                            '{{ $colour }}',
                        @endforeach
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
        @endif
    </script>
    @endif
@endpush

@extends('frontend.layouts.app')

@section('title', 'LogKeeper Stream - ' . $erp->title)

@push('after-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12" id="printable-content">
                <div class="card">
{{--                    <div class="card-header">--}}
{{--                        <h3 class="card-title">Striped Full Width Table</h3>--}}
{{--                    </div>--}}
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th>Title</th>
                                    <td>{{ $erp->title }}</td>
                                </tr>
                                <tr>
                                    <th>Purpose</th>
                                    <td>{{ $erp->purpose }}</td>
                                </tr>
                                <tr>
                                    <th>Remarks</th>
                                    <td>{{ $erp->remarks }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">

                    <div class="card-header">
                        <strong>Logkeeping Stream</strong>
                        <button class="btn btn-primary float-right" id="printButton">Print Table</button>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped" id="logkeeping-data">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Message From</th>
                                    <th>Message To</th>
                                    <th>Event Summary</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody id="logTableBody">
                                @php($last_log_id = 0)
                                @foreach ($logkeeps as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td>{{ $log->message_from }}</td>
                                        <td>{{ $log->message_to }}</td>
                                        <td>{{ $log->event_summary }}</td>
                                        <td>{{ $log->created_at }}</td>
                                    </tr>
                                    @php($last_log_id = $last_log_id < $log->id ? $log->id : $last_log_id)
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    @include('includes.partials._datatables-js')

    <script>
        $(document).ready(function() {
            var last_log_id = {{ $last_log_id }};

            // Function to add a log to the table
            function addLogToTable(log) {
                last_log_id = log.id;
                const newRow = `
            <tr class="animate__animated animate__backInDown">
                <td>${log.id}</td>
                <td>${log.message_from}</td>
                <td>${log.message_to}</td>
                <td>${log.event_summary}</td>
                <td>${log.created_at}</td>
            </tr>
        `;
                $("#logTableBody").prepend(newRow);
            }

            // Function to get new logs from the server
            function getNewLogs(last_id) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('get.logstream', $erp->id) }}?last_log_id=" + last_id,
                    success: function (response) {
                        if (Array.isArray(response)) {
                            // Iterate through new logs and add them to the table
                            response.forEach(function (log) {
                                addLogToTable(log);
                                last_log_id = log.id;
                            });
                        }
                    },
                    error: function (error) {
                        console.error("Error fetching new logs: ", error);
                    }
                });
            }


            // Set an interval to check for new logs every x seconds (e.g., 10 seconds)
            const interval = 5000; // 10 seconds
            setInterval(function () {
                getNewLogs(last_log_id);
            }, interval);

            $("#printButton").click(function () {
                const tableId = "logkeeping-data"; // ID of the table you want to print
                // const printContent = document.getElementById(tableId);
                const printContent = document.getElementById('printable-content');
                const windowUrl = "about:blank";
                const uniqueName = new Date().toISOString();
                const windowName = "Print-" + uniqueName;

                const printWindow = window.open(
                    windowUrl,
                    windowName,
                    "left=50000,top=50000,width=800,height=600" // Adjust the width and height as needed
                );

                {{--    @include('frontend.erps.log_keeping._print-js')--}}
                // Define custom CSS styles for the printed table
                const customStyles = `
      <style>
        table {
          width: 100%;
          border-collapse: collapse;
          border-spacing: 0;
          border: 1px solid #ddd; /* Add border */
          border-radius: 5px; /* Add border radius */
        }
        th, td {
          padding: 8px;
          text-align: left;
          border-bottom: 1px solid #ddd; /* Add border to table cells */
        }
        th {
          background-color: #f2f2f2; /* Add background color to header cells */
        }
      </style>
    `;

                printWindow.document.write("<html><head><title>@yield('title')</title>");
                printWindow.document.write(customStyles); // Apply custom styles
                printWindow.document.write("</head><body >");
                printWindow.document.write(printContent.outerHTML);
                printWindow.document.write("</body></html>");
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();

            });

            function printTable() {
                var printWindow = window.open('', '', 'height=600,width=800');
                printWindow.document.write('<html><head><title>Print Table</title>');
                printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid black; padding: 8px; text-align: left; }</style>');
                printWindow.document.write('</head><body>');
                printWindow.document.write(document.getElementById('dataTable').outerHTML);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            }
        });
    </script>
@endpush

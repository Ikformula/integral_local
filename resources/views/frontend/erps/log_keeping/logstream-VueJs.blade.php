@extends('frontend.layouts.app')

@section('title', $meeting->title .' LogKeeping Stream')

@push('after-styles')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
    <style>
        /* Add your CSS styles for the fade-in animation here */
        .fade-in {
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .fade-in.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th>Purpose</th>
                                    <td>{{ $meeting->purpose }}</td>
                                </tr>
                                <tr>
                                    <th>Remarks</th>
                                    <td>{{ $meeting->remarks }}</td>
                                </tr>
                                <tr>
                                    <th>Date/Time of Meeting</th>
                                    <td>{{ $meeting->created_at->toDayDateTimeString() }}</td>
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
                    <div class="card-body table-responsive p-0" id="app">
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
                            @verbatim
                                <tr v-for="log in logs" :key="log.id" v-if="log.id > lastLogId" v-cloak
                                    :class="{ 'fade-in': true }">
                                    
                                    <td>{{ log.id }}</td>
                                    <td>{{ log.message_from }}</td>
                                    <td>{{ log.message_to }}</td>
                                    <td>{{ log.event_summary }}</td>
                                    <td>{{ log.created_at }}</td>
                                </tr>
                            @endverbatim
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script>
         const logkeeps = @json($logkeeps); // Convert PHP array to JSON
    const app = new Vue({
        el: '#app',
        data: {
            logs: logkeeps, // Initialize logs with the PHP data
            lastLogId: logkeeps.length > 0 ? logkeeps[logkeeps.length - 1].id : 0, // Set lastLogId accordingly
        },
            methods: {
                addLogToTable(log) {
                    this.logs.push(log);
                    this.lastLogId = log.id;
                },
                getNewLogs() {
                    // Make an AJAX request to fetch new logs
                    axios.get("{{ route('get.logstream', $meeting->id) }}", {
                            params: {
                                last_log_id: this.lastLogId,
                            },
                        })
                        .then(response => {
                            if (Array.isArray(response.data)) {
                                response.data.forEach(log => {
                                    this.addLogToTable(log);
                                });
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching new logs: ", error);
                        });
                },
            },
            mounted() {
                setInterval(() => {
                    this.getNewLogs();
                }, 5000); // Fetch new logs every 5 seconds
            },
        });
    </script>


    <script>
        $("#printButton").click(function() {
        const tableId = "logkeeping-data"; // ID of the table you want to print
        const printContent = document.getElementById(tableId);
        const windowUrl = 'about:blank';
        const uniqueName = new Date().toISOString();
        const windowName = 'Print-' + uniqueName;

        const printWindow = window.open(windowUrl, windowName,
            'left=50000,top=50000,width=0,height=0');
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('</head><body >');
        printWindow.document.write(printContent.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
        });
        });
    </script>
@endpush

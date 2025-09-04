@extends('frontend.layouts.app')

@section('title', 'Network Downtime Log Analyzer' )

@section('after-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
@endsection

@section('content')

    <section class="content">
        <div class="container-fluid">
                <h1>Outage Report Generator</h1>
                <div class="file-input">
                    <label for="fileInput" class="form-label">Upload Log File</label>
                    <input type="file" class="form-control" id="fileInput">
                </div>
            <div id="loadingSpinner" class="mt-3 d-none justify-content-center align-items-center">
                <div class="spinner-border text-navy" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <div class="row my-3" id="outageSummary">
                    <!-- Info Cards will be dynamically inserted here -->
                </div>

            <div class="card">
              <div class="card-body">
                  <h2 id="outage-report-header">Outage Details</h2>
                  <table class="table table-bordered" id="outageTable">
                      <thead>
                      <tr>
                          <th>From Time</th>
                          <th>To Time</th>
                          <th>Provider</th>
                          <th>Duration (Seconds)</th>
                          <th>Duration (Minutes)</th>
                          <th>Duration (Hours)</th>
                          <th>Duration (Days)</th>
                          <th>IP Address</th>
                      </tr>
                      </thead>
                      <tbody id="outageTableBody">
                      <!-- Outage data will be inserted here -->
                      </tbody>
                  </table>
              </div>
            </div>
            </div>
    </section>

@endsection

@push('after-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <!--<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.pdf.min.js"></script>-->


    <script>
        const fileInput = document.getElementById('fileInput');
        let logData = '';
        let outages = {};

        fileInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function () {
                // Clear previous data and table contents
                logData = '';
                outages = {};
                clearPreviousReport(); // Clears table, summary, and resets DataTables

                // Show the loading spinner
                document.getElementById('loadingSpinner').classList.remove('d-none');

                logData = reader.result;

                // Use setTimeout to ensure the spinner is shown before processing starts
                setTimeout(() => {
                    processLogFile(logData);  // Process the file after spinner is visible
                }, 100); // Delay the file processing slightly to let the spinner show up
            };

            reader.readAsText(file);
        });

        function clearPreviousReport() {
            // Clear the outage summary
            document.getElementById('outageSummary').innerHTML = '';

            // Clear the outage table body
            const outageTableBody = document.getElementById('outageTableBody');
            if (outageTableBody) {
                outageTableBody.innerHTML = '';
            }

            // Destroy the DataTable if it exists
            if ($.fn.DataTable.isDataTable('#outageTable')) {
                $('#outageTable').DataTable().clear().destroy();
            }
        }

        function processLogFile(data) {
            const lines = data.split('\n');
            const outageEvents = [];
            const activeOutages = {};
            const currentYear = new Date().getFullYear();
            const currentMonth = new Date().getMonth() + 1; // Get the current month (0-based, so add 1)

            lines.forEach(line => {
                const liveToDeadRegex = /Executing : <gateway:gw_live_to_dead> args : <{ "param": "([^"]+)",/;
                const deadToLiveRegex = /Executing : <gateway:gw_dead_to_live> args : <{ "param": "([^"]+)",/;
                const timestampRegex = /\b(\w+ \d+ \d+:\d+:\d+Z)\b/;
                const ipRegex = /"ip": "([^"]+)"/;

                const liveToDeadMatch = line.match(liveToDeadRegex);
                const deadToLiveMatch = line.match(deadToLiveRegex);
                const timestampMatch = line.match(timestampRegex);
                const ipMatch = line.match(ipRegex);

                if (timestampMatch) {
                    const logMonth = new Date(Date.parse(`2024 ${timestampMatch[1].replace('Z', '+0000')}`)).getMonth() + 1;
                    const logYear = logMonth > currentMonth ? currentYear - 1 : currentYear;

                    const timestamp = new Date(Date.parse(`${logYear} ${timestampMatch[1].replace('Z', '+0000')}`));
                    const ip = ipMatch ? ipMatch[1] : null;

                    if (liveToDeadMatch) {
                        const provider = liveToDeadMatch[1];

                        // Start a new outage event
                        if (!activeOutages[provider]) {
                            activeOutages[provider] = { timestamp, ip };
                        }
                    }

                    if (deadToLiveMatch) {
                        const provider = deadToLiveMatch[1];

                        // Close the outage event for this provider
                        if (activeOutages[provider]) {
                            const startEvent = activeOutages[provider];
                            const durationSeconds = (timestamp - startEvent.timestamp) / 1000;
                            const durationMinutes = (durationSeconds / 60).toFixed(2);
                            const durationHours = (durationMinutes / 60).toFixed(2);
                            const durationDays = (durationHours / 24).toFixed(2);

                            outageEvents.push({
                                from_time: startEvent.timestamp,
                                to_time: timestamp,
                                provider: provider,
                                duration_seconds: durationSeconds,
                                duration_minutes: durationMinutes,
                                duration_hours: durationHours,
                                duration_days: durationDays,
                                ip_address: startEvent.ip
                            });

                            // Update the total outage duration per provider, ensuring it's treated as a number
                            if (!outages[provider]) outages[provider] = 0;
                            outages[provider] += parseFloat(durationHours);

                            delete activeOutages[provider]; // Remove the active outage for this provider
                        }
                    }
                }
            });

            generateOutageSummary(outages);
            populateOutageTable(outageEvents);
        }

        function generateOutageSummary(outages) {
            const outageSummaryDiv = document.getElementById('outageSummary');
            outageSummaryDiv.innerHTML = ''; // Clear existing cards

            Object.keys(outages).forEach(provider => {
                const hours = parseFloat(outages[provider]).toFixed(2); // Ensure it's treated as a number
                const cardHtml = `
<div class="col-md-4">
                <div class="info-box shadow">
<span class="info-box-icon bg-navy"><i class="fas fa-poll"></i></span>
    <div class="info-box-content">
        <span class="info-box-text">${removeUnneededChars(provider)}</span>
        <span class="info-box-number">Total Outage Duration: ${hours} hours</span>
    </div>
</div>
</div>`;
                outageSummaryDiv.innerHTML += cardHtml;
            });
        }

        function populateOutageTable(outageEvents) {
            const outageTableBody = document.getElementById('outageTableBody');
            outageTableBody.innerHTML = ''; // Clear existing rows

            outageEvents.forEach(event => {
                const rowHtml = `
            <tr>
                <td>${event.from_time.toISOString()}</td>
                <td>${event.to_time.toISOString()}</td>
                <td>${removeUnneededChars(event.provider)}</td>
                <td>${event.duration_seconds}</td>
                <td>${event.duration_minutes}</td>
                <td>${event.duration_hours}</td>
                <td>${event.duration_days}</td>
                <td>${event.ip_address || 'N/A'}</td>
            </tr>`;
                outageTableBody.innerHTML += rowHtml;
            });

            // Initialize DataTables with export buttons
            $('#outageTable').DataTable({
                destroy: true,  // Ensures table is re-initialized after every upload
                dom: 'Bfrtip',  // Add export buttons
                buttons: [
                    'copy', 'csv', 'excel', 'print'
                ],
                order: [],
                responsive: true
            });

            // Hide the loading spinner when done
            document.getElementById('loadingSpinner').classList.add('d-none');
            $('#outage-report-header').html('Outage Report');
        }

        function removeUnneededChars(text){
            text = text.replace('@', '');
            return text.replace('%20', ' ');
        }
    </script>
@endpush

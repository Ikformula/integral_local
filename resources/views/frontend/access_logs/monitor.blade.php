@extends('frontend.layouts.app')

@section('title', 'Activity Logs Monitor')

@push('after-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card arik-card">
                        <div class="card-header">
                            <h3 class="card-title">User Activity Logs</h3>
                        </div>

                        <div class="card-body">
                            <table class="table table-striped w-100" id="logs-table">
                                <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>URL</th>
                                    <th>Method</th>
                                    <th>Duration (seconds)</th>
                                    <th>Accessed At</th>
                                    <th>IP Address</th>
                                    <th>Location</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $offset = strlen(config('app.url'));
                                @endphp
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->user->name ?? 'Guest' }}</td>
                                        <td>{{ $log->user->email ?? 'N/A' }}</td>
                                        <td>{{ substr($log->url, $offset)  }}</td>
                                        <td>{{ $log->method }}</td>
                                        <td>{{ $log->duration ?? 'N/A' }}</td>
                                        <td>{{ $log->accessed_at->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No logs available</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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


{{--    <script>--}}
{{--        $(document).ready(function () {--}}
{{--            --}}
{{--        });--}}
{{--    </script>--}}

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let table = document.getElementById("logs-table"); // Replace with your actual table ID
            let ipCells = table.querySelectorAll("tbody tr td:nth-child(7)"); // IP Address column
            let ipMap = new Map();

            ipCells.forEach(cell => {
                let ip = cell.textContent.trim();
                if (ip) {
                    ipMap.set(ip, cell.parentElement); // Map unique IPs to their rows
                }
            });

            let uniqueIps = Array.from(ipMap.keys());

            console.log("Filtered Unique IPs:", uniqueIps); // Debug extracted IPs

            if (uniqueIps.length === 0) return;

            fetch("http://ip-api.com/batch", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(uniqueIps)
            })
                .then(response => response.json())
                .then(data => {
                    console.log("API Response:", data); // Debug full API response

                    data.forEach(entry => {
                        if (entry.status === "success") {
                            let matchingRows = [...table.querySelectorAll("tbody tr")].filter(tr => {
                                return tr.children[6].textContent.trim() === entry.query; // Ensure correct column match
                            });

                            matchingRows.forEach(row => {
                                let locationCell = row.children[7]; // Ensure this is the correct column index
                                if (locationCell) {
                                    locationCell.textContent = `${entry.city}, ${entry.country}`;
                                    if (entry.country !== 'Nigeria') {
                                        row.classList.add('non-nigeria-row');
                                    }
                                }
                            });
                        }
                    });

                    // Add CSS for non-Nigeria rows
                    const style = document.createElement('style');
                    style.innerHTML = `.non-nigeria-row { background-color: #ffe5e5 !important; }`;
                    document.head.appendChild(style);

                    var Dtable = new DataTable('.table', {
                        paging: false,
                        scrollY: 465,
                        order: [], // preserve backend order, no initial sort
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

                })
                .catch(error => console.error("Error fetching IP data:", error));

        });
    </script>





@endpush

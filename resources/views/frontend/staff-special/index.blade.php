@extends('frontend.layouts.app')

@section('title', 'Staff Members')

@push('after-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Staff List</h3>
                <button class="btn btn-primary float-right" hx-get="{{ route('frontend.user.staff.create') }}" hx-target="#modal-content">
                    Add Staff
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                @include('frontend.staff-special.table')
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Create/Edit -->
    <div class="modal fade" id="staff-modal" tabindex="-1" role="dialog" aria-labelledby="staff-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="modal-content">
                <!-- Content will be loaded here -->
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

    <script src="https://unpkg.com/htmx.org@1.9.2"></script>
    <Script>
        let staffTable;

        $(document).ready(function() {
            initializeDataTable();

            htmx.on("htmx:afterSwap", function(event) {
                if (event.detail.target.id === "modal-content") {
                    $('#staff-modal').modal('show');
                } else if (event.detail.target.id === "staff-table") {
                    $('#staff-modal').modal('hide');
                    reinitializeDataTable();
                }
            });

            htmx.on("htmx:beforeSwap", function(event) {
                if (event.detail.target.id === "modal-content" && !event.detail.xhr.response) {
                    $('#staff-modal').modal('hide');
                    event.detail.shouldSwap = false;
                }
            });

            htmx.on("htmx:responseError", function(event) {
                console.error("An error occurred:", event.detail.error);
                showInstantToast("An error occurred. Please try again.", "error");
            });
        });

        function initializeDataTable() {
            staffTable  = new DataTable('#staff-table', {
                "paging": true,
                "perPage": 50,
                layout: {
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                }
            });
        }

        function reinitializeDataTable() {
            // if ($.fn.DataTable.isDataTable('#staff-table')) {
                staffTable.destroy();
            //     showInstantToast("Table destroyed.", "info");
            // }
            initializeDataTable();
        }
    </script>
@endpush

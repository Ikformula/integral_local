@extends('frontend.layouts.app')

@section('title', 'IT Assets' )

@push('after-styles')
    @include('includes.partials._datatables-css')
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>IT Assets</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped" id="it-assets">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Brand</th>
                                <th>Device Type</th>
                                <th>Model</th>
                                <th>Serial Number</th>
                                <th>Asset Tag</th>
                                <th>Staff</th>
                                <th>Location</th>
                                <th>Department</th>
                                <th>Entered By</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($it_assets as $it_asset)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $it_asset->brand }}</td>
                                    <td>{{ $it_asset->device_type }}</td>
                                    <td>{{ $it_asset->model }} </td>
                                    <td>{{ $it_asset->serial_number }}</td>
                                    <td>{{ $it_asset->asset_tag ?? 'null' }}</td>
                                    <td>{{ !is_null($it_asset->staffMember) ? $it_asset->staffMember->name.', '.$it_asset->staff_ara_id : 'None'}}</td>
                                    <td>{{ $it_asset->office_location }}</td>
                                    <td>{{ $it_asset->department_name }}</td>
                                    <td>{{ $it_asset->user->full_name ?? '' }}</td>
                                    <td><span class="badge badge-{{ $it_asset->status == 'in service' ? 'success' : 'warning' }}">{{ $it_asset->status }}</span></td>
                                    <td>
                                        @include('frontend.it_assets._it-assets-action-buttons')
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{ $it_assets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    @include('includes.partials._datatables-js')

    <script>
        // var table = $("#it-assets").DataTable({
        //     // "responsive": false, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465,
        //     // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        //     "responsive": false,
        //     "lengthChange": false,
        //     "autoWidth": false,
        //     paging: false,
        //     scrollY: 465,
        //     scrollX: true,
        //     scrollCollapse: true,
        //     "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        // }).buttons().container().appendTo('#it-assets_wrapper .col-md-6:eq(0)');

        $(document).ready(function () {
            // Setup - add a text input to each footer cell
            $('#it-assets thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#it-assets thead');

            var table = $('#it-assets').DataTable({
                 "responsive": true, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465, scrollX: true, scrollCollapse: true, "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],

                orderCellsTop: true,
                fixedHeader: true,
                initComplete: function () {
                    var api = this.api();
                    let th_count = 1;
                    const filtered_th = [1, 12];
                    // For each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            if(!filtered_th.includes(th_count)) {
                            // Set the header cell to contain the input element
                            var cell = $('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            var title = $(cell).text();
                            $(cell).html('<input type="text" placeholder="' + title + '" class="form-control-sm" />');

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
            }).buttons().container().appendTo('#it-assets_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush

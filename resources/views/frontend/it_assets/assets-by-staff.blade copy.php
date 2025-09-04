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
                        <h4>Staff IT Asset Counts</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped" id="it-assets">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>ARA ID</th>
                                <th>Staff</th>
                                <th>Department</th>
                                <th>Location</th>
                                <th>Total</th>
                                @foreach($device_types as $device_type)
                                    <th>{{ $device_type }}</th>
                                @endforeach
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{-- @if(1 < 0) --}}
                            @foreach($staff_members as $staff_member)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $staff_member->staff_ara_id }}</td>
                                    <td>{{ $staff_member->name }}</td>
                                    <td>{{ $staff_member->department_name }}</td>
                                    <td>{{ $staff_member->location }}</td>
                                    <td>{{ $staff_assets_count[$staff_member->staff_ara_id]['Total'] }}</td>
                                    @foreach($device_types as $device_type)
                                        <td>{{ $staff_assets_count[$staff_member->staff_ara_id][$device_type] }}</td>
                                    @endforeach
                                    <td>
                                        <a href="{{ route('frontend.it_assets.staff.it.assets') }}?staff_ara_id={{ $staff_member->staff_ara_id }}" class="btn btn-xs btn-primary">View Staff Assets</a>
                                    </td>
                                </tr>
                            @endforeach
                            {{-- @endif --}}
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
            {{-- let staff_members = @json($staff_members);
            let device_types = @json($device_types);
            let it_assets = @json($it_assets); --}}



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
                    const filtered_th = [2, 3, 4, 5];
                    // For each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            if(filtered_th.includes(th_count)) {
                                // Set the header cell to contain the input element
                                var cell = $('.filters th').eq(
                                    $(api.column(colIdx).header()).index()
                                );
                                var title = $(cell).text();
                                $(cell).html('<input type="text" class="form-control" placeholder="' + title + '" />');

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

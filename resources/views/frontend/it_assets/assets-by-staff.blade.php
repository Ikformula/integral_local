@extends('frontend.layouts.app')

@section('title', 'IT Assets By Staff/Departments' )

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
            <th>Location In HQ</th>
            <th>Total</th>
            @foreach($device_types as $device_type)
                <th>{{ $device_type->device_type }}</th>
            @endforeach
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="table-body">
        @foreach($staff_members as $key => $staff_member)
            <tr id="row-{{ $staff_member->staff_ara_id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $staff_member->staff_ara_id }}</td>
                <td>{{ $staff_member->surname }} {{ $staff_member->other_names }}</td>
                <td>{{ $staff_member->department_name }}</td>
                <td>{{ $staff_member->location_in_hq }}</td>
                <td id="total-{{ $staff_member->staff_ara_id }}">0</td>
                @foreach($device_types as $device_type)
                    <td id="{{ $staff_member->staff_ara_id }}-{{ $device_type->device_type }}">0</td>
                @endforeach
                <td><a href="{{ route('frontend.it_assets.staff.it.assets')}}?staff_ara_id={{ $staff_member->staff_ara_id }}" class="btn btn-info btn-sm">View</a></td>
            </tr>
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

    function highlightZeroValueCells(cell_id, cell_value){
        if(cell_value == 0){
                document.getElementById(cell_id).style.backgroundColor  = 'rgb(241 198 206 / 91%)';
        }
    }

        $(document).ready(function () {

    var staffMembers = {!! $staff_members->toJson() !!};
    var itAssets = {!! $it_assets->toJson() !!};
    var deviceTypes = {!! $device_types->toJson() !!};

    // Create an object to store the counts
    var counts = {};

    // Initialize counts with zeros
    staffMembers.forEach(function(staffMember) {
        counts[staffMember.staff_ara_id] = {
            total: 0
        };
        deviceTypes.forEach(function(deviceType) {
            counts[staffMember.staff_ara_id][deviceType.device_type] = 0;
        });
    });

    // Calculate counts
    itAssets.forEach(function(itAsset) {
        var staffAraId = itAsset.staff_ara_id;
        var deviceType = itAsset.device_type;

        if (counts[staffAraId]) {
            counts[staffAraId][deviceType]++;
            counts[staffAraId].total++;
        }
    });


    // Populate the table cells with counts
    staffMembers.forEach(function(staffMember) {
        var staff_ara_id = staffMember.staff_ara_id;
        var staffData = counts[staff_ara_id];

        document.getElementById('total-' + staff_ara_id).textContent = staffData.total;
        highlightZeroValueCells('total-' + staff_ara_id, staffData.total);

        deviceTypes.forEach(function(deviceType) {
            var cellId = staff_ara_id + '-' + deviceType.device_type;
            document.getElementById(cellId).textContent = staffData[deviceType.device_type];
            highlightZeroValueCells(cellId, staffData[deviceType.device_type]);
        });
    });





// dataTable
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
                    const filtered_th = [2, 3, 4, 5, 6];
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

                                if(th_count === 4){
                                    $(cell).html(`<input type="search" list="department_names_list" class="form-control-sm" placeholder="${title}" />
                                    <datalist id="department_names_list">
                                    @include('includes.partials._departments-option-list')
                                    </datalist>

                                    `);
                                }else{
                                $(cell).html('<input type="text" class="form-control-sm" placeholder="' + title + '" />');
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
            }).buttons().container().appendTo('#it-assets_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush

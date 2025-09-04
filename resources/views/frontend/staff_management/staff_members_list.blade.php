@extends('frontend.layouts.app')

@section('title', 'Staff Members Information')

@push('after-styles')
    @include('includes.partials._datatables-css')
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach($stats as $stat)
                <div class="col-sm-4">
                    @component('frontend.components.dashboard_stat_widget', ['icon' => $stat['icon'], 'title' => $stat['title']])
                        {{ $stat['value'] }}
                    @endcomponent
                </div>
                @endforeach
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Staff Members Information</div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm" id="staff-members-data">
                                    <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Surname</th>
                                        <th>Other Names</th>
                                        <th>ARA ID</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Job Title</th>
                                        <TH>Category</TH>
                                        <th>ID Card</th>
                                    </tr>
                                    </thead>
                                    <tbody class="pb-4">
                                    @foreach($staff_members as $staff_member)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $staff_member->surname ?? ''}}</td>
                                            <td>{{ $staff_member->other_names ?? '' }}</td>
                                            <td>ARA{{ $staff_member->staff_ara_id ?? '' }}</td>
                                            <td>{{ $staff_member->email ?? '' }}</td>
                                            <td>{{ $staff_member->department_name ?? '' }}</td>
                                            <td>{{ $staff_member->job_title ?? '' }}</td>
                                            <td>{{ $staff_member->employment_category ?? '' }}</td>
                                            <td>
                                                <div class="btn-group dropup">
                                                    <a href="{{ route('frontend.user.profile.editIDcard') }}?staff_ara_id={{ $staff_member->staff_ara_id }}" class="btn btn-sm btn-{{ !is_null($staff_member->id_card_file_name) ? 'info' : 'warning' }}">{{ !is_null($staff_member->id_card_file_name) ? 'View/Edit Profile' : 'Add ID Card' }}</a>
                                                    <button type="button" class="btn btn-danger dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
{{--                                                        <a class="dropdown-item" href="{{ route('frontend.user.profile.editIDcard') }}?staff_ara_id={{ $staff_member->staff_ara_id }}&section=edit-manager" target="_blank">Edit Manager</a>--}}
                                                        <a class="dropdown-item" href="{{ route('frontend.it_assets.staff.it.assets') }}?staff_ara_id={{ $staff_member->staff_ara_id }}" target="_blank">IT Assets</a>
                                                        </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/. container-fluid -->


    </section>
@endsection


@push('after-scripts')
    @include('includes.partials._datatables-js')

    <script>
        // $("#staff-members-data").DataTable({
        //     "responsive": false, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465,
        //     "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        // }).buttons().container().appendTo('#staff-members-data_wrapper .col-md-6:eq(0)');

        $(document).ready(function () {
            // Setup - add a text input to each footer cell
            $('#staff-members-data thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#staff-members-data thead');

            var table = $('#staff-members-data').DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465, scrollX: true, scrollCollapse: true, "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],

                orderCellsTop: true,
                fixedHeader: true,
                initComplete: function () {
                    var api = this.api();

                    // For each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            // Set the header cell to contain the input element
                            var cell = $('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            var title = $(cell).text();
                            $(cell).html('<input type="text" placeholder="' + title + '" />');

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
                        });
                },
            }).buttons().container().appendTo('#staff-members-data_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush

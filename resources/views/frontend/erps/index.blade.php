@extends('frontend.layouts.app')

@section('title', 'ERP-CMC Logkeeper')

@push('after-styles')
    @include('includes.partials._datatables-css')
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">@yield('title') <button type="button" data-toggle="modal" data-target="#modal-create-erp" class="btn bg-navy float-right">Create New ERP</button></div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm" id="erps">
                                    <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Title</th>
                                        <th>Purpose</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($erps as $erp)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $erp->title ?? ''}}</td>
                                            <td>{{ $erp->purpose ?? '' }}</td>
                                            <td>{{ $erp->created_at->toDayDateTimeString() ?? '' }}</td>
                                            <td>
                                                @if($logged_in_user->can('enter logkeeps'))
                                                <form action="{{ route('frontend.log_keeping.delete.erp', $erp) }}" class="form-inline" method="POST" onsubmit="return confirm('Are you sure you want to delete this ERP? All the logs under it will also be deleted.')">
                                                    @method('DELETE')
                                                    @csrf
                                                    <a href="{{ route('frontend.log_keeping.show.erp', $erp)}}" class="btn btn-sm btn-primary mr-2">View</a>
                                                    @endif
                                                    <a href="{{ route('frontend.log_keeping.logstream', $erp)}}" class="btn btn-sm btn-info mr-2">Logstream</a>
                                                    @if($logged_in_user->can('enter logkeeps'))
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                                @endif
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

    <div class="modal fade" id="modal-create-erp">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create ERP</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="{{ route('frontend.log_keeping.store.erp') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control">
                </div>

                <div class="form-group">
                    <label>Purpose</label>
                    <input type="text" name="purpose" class="form-control">
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-info float-right">Save</button>
            </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
@endsection


@push('after-scripts')
    @include('includes.partials._datatables-js')

    <script>
        // $("#erps").DataTable({
        //     "responsive": false, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465,
        //     "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        // }).buttons().container().appendTo('#erps_wrapper .col-md-6:eq(0)');

        $(document).ready(function () {
            // Setup - add a text input to each footer cell
            $('#erps thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#erps thead');

            var table = $('#erps').DataTable({
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
            }).buttons().container().appendTo('#erps_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush

@extends('frontend.layouts.app')

@section('title', 'Logs' )

@push('after-styles')
    @include('includes.partials._datatables-css')
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Call Logs</div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-sm" id="call-center-logs">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>DateTime</th>
                                                <th>Passenger Name</th>
                                                <th>Passenger Phone</th>
                                                <th>Agent</th>
                                                <th>Type</th>
                                                <th>Purpose</th>
                                                <th>View</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($logs as $log)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $log->date_of_call }}</td>
                                                    <td>{{ $log->passenger_name }}</td>
                                                    <td>{{ $log->passenger_mobile_number }}</td>
                                                    <td>{{ $log->agent->full_name }}</td>
                                                    <td>{{ $log->type_of_call }}</td>
                                                    <td>{{ $log->call_purpose }}</td>
                                                    <td><a href="{{ route('frontend.call_center.view.log', $log) }}" class="btn btn-sm btn-primary">View <i class="far fa-eye"></i> </a> </td>
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
        // $('#call-center-logs').DataTable({
        // });

        $("#call-center-logs").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#call-center-logs_wrapper .col-md-6:eq(0)');
    </script>

    <script type="text/javascript">
        $(function () {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

        });
    </script>
@endpush

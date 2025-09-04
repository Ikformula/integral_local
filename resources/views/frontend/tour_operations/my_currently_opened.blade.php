@extends('frontend.layouts.app')

@section('title','Currently Opened By Me' )

@push('after-styles')
    @include('includes.partials._datatables-css')
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            Passengers List
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-striped" id="passengers-data">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Surname</th>
                                        <th>First Name</th>
                                        <th>Other Name</th>
                                        <th>Gender</th>
                                        <th>Nationality</th>
                                        <th>Date of Birth</th>
                                        <th>Passport Number</th>
                                        <th>Place of Issue</th>
                                        <th>Expiry Date</th>
                                        <th>Destination</th>
                                        <th>Class</th>
                                        <th>SSR Group</th>
                                        <th>Visa Issuance Date</th>
                                        <th>Visa Expiry Date</th>
                                        <th>Proposed Flight Date</th>
                                        <th>Ticket ID</th>
                                        <th>View</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($passengers as $passenger)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $passenger->surname }}</td>
                                            <td>{{ $passenger->firstname }}</td>
                                            <td>{{ $passenger->other_name }}</td>
                                            <td>{{ $passenger->gender }}</td>
                                            <td>{{ $passenger->nationality }}</td>
                                            <td>{{ $passenger->date_of_birth }}</td>
                                            <td>{{ $passenger->passport_number }}</td>
                                            <td>{{ $passenger->place_of_issue }}</td>
                                            <td>{{ $passenger->expiry_date }}</td>
                                            <td>{{ $passenger->destination }}</td>
                                            <td>{{ $passenger->class }}</td>
                                            <td>{{ $passenger->ssr_group }}</td>
                                            <td>{{ $passenger->visa_date_of_issuance }}</td>
                                            <td>{{ $passenger->visa_date_of_expiry }}</td>
                                            <td>{{ $passenger->proposed_flight_date }}</td>
                                            <td>{{ $passenger->ticket_id }}</td>
                                            <td><a href="{{ route('frontend.tour_operations.passengers.show', $passenger) }}" class="btn btn-info">View</a> </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer">
                            {{ $passengers->links() }}
                        </div>
                    </div>

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </div><!--/. container-fluid -->
    </section>



@endsection

@push('after-scripts')
    @include('includes.partials._datatables-js')

    <script>

        $("#passengers-data").DataTable({
            "responsive": false, "lengthChange": false, "autoWidth": true, paging: false, scrollY: 465, scrollX: true, scrollCollapse: true, "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"], fixedColumns: {
                left: 3,
            }
        }).buttons().container().appendTo('#passengers-data_wrapper .col-md-6:eq(0)');
    </script>

    <script>

    </script>
@endpush

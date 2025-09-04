@extends('frontend.layouts.app')

@section('title', 'View Call Log' )

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">View a Call Log</div>
                        <div class="card-body p-0">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <td><strong>Agent</strong></td>
                                    <td>{{ $log->agent->full_name ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>Passenger's Name</strong></td>
                                    <td>{{ $log->passenger_name ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>Passenger's Mobile Number</strong></td>
                                    <td>{{ $log->passenger_mobile_number ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>Passenger's Email</strong></td>
                                    <td>{{ $log->passenger_email_address ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>Passenger's Location</strong></td>
                                    <td>{{ $log->passenger_location ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>Ticket Fare</strong></td>
                                    <td>{{ $log->ticket_fare ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>Date of Call</strong></td>
                                    <td>{{ $log->date_of_call->toDayDateTimeString() ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>Flight Route</strong></td>
                                    <td>{{ $log->flight_route ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>Flight Time</strong></td>
                                    <td>{{ $log->flight_time ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>PNR</strong></td>
                                    <td>{{ $log->pnr ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>Class of Booking</strong></td>
                                    <td>{{ $log->class_of_booking ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>Call Purpose</strong></td>
                                    <td>{{ $log->call_purpose ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>Type of Call</strong></td>
                                    <td>{{ $log->type_of_call ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>Footnote</strong></td>
                                    <td>{{ $log->footnote ?? '' }}</td>
                                </tr>
                                <tr>

                                    <td><strong>Agent Phone Number</strong></td>
                                    <td>{{ $log->receiving_phone_number ?? '' }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Supervisor(s) on Duty</strong></td>
                                    <td>{{ $log->supervisors ?? '' }}</td>
                                </tr>
                                <tr>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/. container-fluid -->
    </section>
@endsection


@extends('frontend.layouts.app')

@section('title', 'ECS Flight Transaction Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('frontend.ecs_flight_transactions.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('frontend.ecs_flight_transactions.edit', $ecs_flight_transaction->id) }}" class="btn btn-primary">Edit</a>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">ECS Flight Transaction Details</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Booking Reference</th>
                    <td>{{ $ecs_flight_transaction->booking_reference }}</td>
                </tr>
                <tr>
                    <th>Client</th>
                    <td>{{ $ecs_flight_transaction->client_idRelation->name_and_balance ?? '' }}</td>
                </tr>
                <tr>
                    <th>Penalties</th>
                    <td>{{ $ecs_flight_transaction->penalties }}</td>
                </tr>
                <tr>
                    <th>Ticket Fare</th>
                    <td>{{ number_format($ecs_flight_transaction->ticket_fare) }}</td>
                </tr>
                <tr>
                    <th>Service Fee</th>
                    <td>{{ number_format($ecs_flight_transaction->service_fee) }}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ optional($ecs_flight_transaction->for_date)->toDateString() }}</td>
                </tr>
                <tr>
                    <th>Agent</th>
                    <td>{{ $ecs_flight_transaction->agentUser->full_name ?? '' }}</td>
                </tr>
                <tr>
                    <th>Remarks</th>
                    <td>{!! $ecs_flight_transaction->remarks !!}</td>
                </tr>
            </table>
            <h5 class="mt-4">Flights</h5>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Flight</th>
                        <th>Class</th>
                        <th>Date</th>
                        <th>Depart From</th>
                        <th>Departure Time</th>
                        <th>Arrive At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ecs_flight_transaction->flights as $flight)
                    <tr>
                        <td>{{ $flight->flight }}</td>
                        <td>{{ $flight->class }}</td>
                        <td>{{ $flight->flight_date }}</td>
                        <td>{{ $flight->depart_from }}</td>
                        <td>{{ $flight->departure_time }}</td>
                        <td>{{ $flight->arrive_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <h5 class="mt-4">Taxes</h5>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Tax Name</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ecs_flight_transaction->taxes as $tax)
                    <tr>
                        <td>{{ $tax->tax_name }}</td>
                        <td>{{ number_format($tax->amount) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
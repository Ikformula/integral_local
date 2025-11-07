<!-- resources/views/frontend/ecs_bookings/show.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Booking Details')

@push('after-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">

    <style>
        @keyframes flash-yellow {
            0%, 100% {
                background-color: inherit; /* Original background */
            }
            50% {
                background-color: yellow;
            }
        }

        /* Class to trigger flashing */
        .flash-row {
            animation: flash-yellow 1s ease-in-out 5; /* 1s per cycle, repeat 5 times (~5s total) */
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Booking Details</h3>
                    </div>
                    <div class="card-body">

                        <p><strong>Booking Reference:</strong> {{ $ecs_booking->booking_reference }}</p>
                        <p><strong>Penalties:</strong> {{ checkIntNumber($ecs_booking->penalties) }}</p>
                        <p><strong>Ticket Fare:</strong> {{ checkIntNumber($ecs_booking->ticket_fare) }}</p>
                        <p><strong>Remarks:</strong> {!! $ecs_booking->remarks !!}</p>
                        <p><strong>Date:</strong> {{ $ecs_booking->for_date->toDateString() }}</p>
                        <p>
                            <strong>Agent:</strong> {{ $ecs_booking->agent_user_idRelation ? $ecs_booking->agent_user_idRelation->full_name : '' }}
                        </p>
                        <p>
                            <strong>Client:</strong>
                        @if(!$logged_in_user->isEcsClient)
                            <a href="{{ route('frontend.ecs_clients.show', $ecs_booking->client_id) }}" target="_blank">{{ $ecs_booking->client_idRelation->name_and_balance }} <i class="fa-solid fa-up-right-from-square"></i></a>
                        @else
                            {{ $ecs_booking->client_idRelation->name_and_balance }}
                            @endif
                        </p>

                        @if($ecs_booking->flight_transactions->count())
                            @php
                            $total_fare = $ecs_booking->totalFare();
                            $total_service_fee = $ecs_booking->totalServiceCharge();
                            $total_taxes = $ecs_booking->totalTaxes();
                            @endphp
                            <p><strong>Total Fare:</strong> {{ number_format($total_fare) }}</p>
                            <p><strong>Total Service Charge:</strong> {{ number_format($total_service_fee) }}</p>
                            <p><strong>Total Fare + Service Charge + Taxes:</strong> {{ number_format($total_fare + $total_service_fee + $total_taxes) }}</p>
                        @endif

                        @if(!$logged_in_user->isEcsClient)
                            <a href="{{ route('frontend.ecs_bookings.index') }}" class="btn btn-secondary">Back</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @php
            $client = $ecs_booking->client_idRelation;
        @endphp
        @if($client->taxes() && count($client->taxes()))
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Taxes</h3>
                        </div>
                        <div class="card-body">
                                <div class="row">
                                @foreach($client->taxes() as $tax)
                                    @php $tax_values[$tax] = $ecs_booking->taxField($tax); @endphp
                                    <div class="col">
                                        <div class="form-group">
                                            <label>{{ strtoupper($tax) }}</label>
                                            <input type="text" name="tax[{{ $tax }}]" @if(!is_null($tax_values[$tax])) value="{{ number_format($tax_values[$tax]->amount) }}" @endif class="form-control" readonly>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Flights</h3>
                        @if(!$logged_in_user->isEcsClient)
                            <button class="btn btn-sm btn-primary float-right" data-toggle="modal"
                                    data-target="#modalCreate-ecs_flights">Add New Flight
                            </button>
                        @endif
                    </div>
                    <div class="card-body table-responsive">
                        @php $ecs_flights = $ecs_booking->flights @endphp
                        <table id="tbl" class="table text-nowrap table-bordered w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                {{--                                    <th>Booking</th>--}}
                                {{--                                    <th>Client</th>--}}
                                {{--                                    <th>Booking Reference</th>--}}
                                <th>Flight</th>
                                <th>Class</th>
                                <th>Flight Date</th>
                                <th>Depart From</th>
                                <th>Departure Time</th>
                                <th>Arrive At</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ecs_flights as $key => $ecs_flightsItem)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    {{--                                        <td>{{ $ecs_flightsItem->ecs_booking_idRelation ? $ecs_flightsItem->ecs_booking_idRelation->booking_reference : '' }}</td>--}}
                                    {{--                                        <td>{{ $ecs_flightsItem->client_idRelation ? $ecs_flightsItem->client_idRelation->name : '' }}</td>--}}
                                    {{--                                        <td>{{ $ecs_flightsItem->booking_reference }}</td>--}}
                                    <td>{{ $ecs_flightsItem->flight }}</td>
                                    <td>{{ $ecs_flightsItem->class }}</td>
                                    <td>{{ $ecs_flightsItem->flight_date }}</td>
                                    <td>{{ $ecs_flightsItem->depart_from }}</td>
                                    <td>{{ $ecs_flightsItem->departure_time }}</td>
                                    <td>{{ $ecs_flightsItem->arrive_at }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-toggle="modal"
                                                data-target="#modalEdit-ecs_flights-{{ $ecs_flightsItem->id }}">Edit
                                        </button>
                                        <form
                                            action="{{ route('frontend.ecs_flights_ajax.destroy', $ecs_flightsItem->id) }}"
                                            method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modalEdit-ecs_flights-{{ $ecs_flightsItem->id }}"
                                     tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form
                                                action="{{ route('frontend.ecs_flights_ajax.update', $ecs_flightsItem->id) }}"
                                                method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Flight</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group"><label>Flight</label>
                                                        <input type="text" class="form-control" name="flight"
                                                               value="{{ $ecs_flightsItem->flight }}"></div>
                                                    <div class="form-group"><label>Class</label>
                                                        <input type="text" class="form-control" name="class"
                                                               value="{{ $ecs_flightsItem->class }}"></div>
                                                    <div class="form-group"><label>Flight Date</label>
                                                        <input type="date" class="form-control" name="flight_date"
                                                               value="{{ $ecs_flightsItem->flight_date }}"></div>
                                                    <div class="form-group"><label>Depart From</label>
                                                        <input type="text" class="form-control" name="depart_from"
                                                               value="{{ $ecs_flightsItem->depart_from }}"></div>
                                                    <div class="form-group"><label>Departure Time</label>
                                                        <input type="datetime-local" class="form-control"
                                                               name="departure_time"
                                                               value="{{ $ecs_flightsItem->departure_time }}"></div>
                                                    <div class="form-group"><label>Arrive At</label>
                                                        <input type="datetime-local" class="form-control"
                                                               name="arrive_at"
                                                               value="{{ $ecs_flightsItem->arrive_at }}"></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Flight Transactions List</h3>
                        <div class="card-tools">
                            @if(!$logged_in_user->isEcsClient)
                                <button class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#modalCreate-ecs_flight_transactions">Add New Transaction
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="tbl" class="table text-nowrap table-bordered w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Ticket Number</th>
                                <th>Trx Id</th>
                                <th>Is Cancelled</th>
                                <th>Service Fee</th>
                                <th>Client Approved At</th>
                                <th>Approver Client By</th>
                                @if(1 < 0)
                                    <th>Actions</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $ecs_flight_transactions = $ecs_booking->flight_transactions;
                            @endphp
                            @foreach($ecs_flight_transactions as $key => $ecs_flight_transactionsItem)
                                <tr class="{{ isset($_GET['ticket_number']) ? 'flash-row' : '' }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $ecs_flight_transactionsItem->name }}</td>
                                    <td>{{ $ecs_flight_transactionsItem->ticket_number }}</td>
                                    <td>{{ $ecs_flight_transactionsItem->trx_id }}</td>
                                    <td>{{ $ecs_flight_transactionsItem->is_cancelled }}</td>
                                    <td>{{ checkIntNumber($ecs_flight_transactionsItem->service_fee) }}</td>
                                    <td>{{ $ecs_flight_transactionsItem->client_approved_at }}</td>
                                    <td>{{ $ecs_flight_transactionsItem->client_approver_idRelation ? $ecs_flight_transactionsItem->client_approver_idRelation->full_name : '' }}</td>

                                    @if(1 < 0)
                                        <td>
                                            @if(is_null($ecs_flight_transactionsItem->client_approved_at) && !$logged_in_user->isEcsClient)
                                                <button class="btn btn-sm btn-info" data-toggle="modal"
                                                        data-target="#modalEdit-ecs_flight_transactions-{{ $ecs_flight_transactionsItem->id }}">
                                                    Edit
                                                </button>
                                                <form
                                                    action="{{ route('frontend.ecs_flight_transactions.destroy', $ecs_flight_transactionsItem->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                                @if(is_null($ecs_flight_transactionsItem->client_approved_at) && !$logged_in_user->isEcsClient)
                                    <div class="modal fade"
                                         id="modalEdit-ecs_flight_transactions-{{ $ecs_flight_transactionsItem->id }}"
                                         tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form
                                                    action="{{ route('frontend.ecs_flight_transactions.update', $ecs_flight_transactionsItem->id) }}"
                                                    method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Transaction</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group"><label>Name</label>
                                                            <input type="text" class="form-control" name="name"
                                                                   value="{{ $ecs_flight_transactionsItem->name }}">
                                                        </div>
                                                        <div class="form-group"><label>Ticket Number</label>
                                                            <input type="text" class="form-control" name="ticket_number"
                                                                   value="{{ $ecs_flight_transactionsItem->ticket_number }}">
                                                        </div>
{{--                                                        <div class="form-group"><label>Trx Id</label>--}}
{{--                                                            <input type="text" class="form-control" name="trx_id"--}}
{{--                                                                   value="{{ $ecs_flight_transactionsItem->trx_id }}">--}}
{{--                                                        </div>--}}
                                                        <div class="form-group"><label>Is Cancelled</label><br>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                       name="is_cancelled"
                                                                       value="no" {{ $ecs_flight_transactionsItem->is_cancelled=='no'?'checked':'' }}>
                                                                <label class="form-check-label">no</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                       name="is_cancelled"
                                                                       value="yes" {{ $ecs_flight_transactionsItem->is_cancelled=='yes'?'checked':'' }}>
                                                                <label class="form-check-label">yes</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group"><label>Service Fee</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                   name="service_fee"
                                                                   value="{{ $ecs_flight_transactionsItem->service_fee }}">
                                                        </div>
                                                        <input type="hidden" name="for_date" value="{{ $ecs_booking->for_date->toDateString() }}">
                                                        {{--                                                        <div class="form-group"><label>Client Approved At</label>--}}
                                                        {{--                                                            <input type="datetime-local" class="form-control" name="client_approved_at" value="{{ $ecs_flight_transactionsItem->client_approved_at }}"></div>--}}
                                                        {{--                                                        <div class="form-group"><label>Client Approver</label>--}}
                                                        {{--                                                            <select class="form-control" name="client_approver_id">--}}
                                                        {{--                                                                <option value="">-- Select --</option>--}}
                                                        {{--                                                                @foreach(\App\Models\EcsClientUser::where('client_id', $ecs_booking->client_id)->get() as $opt)--}}
                                                        {{--                                                                    <option value="{{ $opt->id }}" {{ $opt->id==$ecs_flight_transactionsItem->client_approver_id?'selected':'' }}>{{ $opt->user->full_name }}</option>--}}
                                                        {{--                                                                @endforeach--}}
                                                        {{--                                                            </select></div>--}}

                                                        <div class="input-group">
                                                            <label for="">Ticket Fare</label>
                                                            <input type="text" class="form-control" name="ticket_fare"
                                                                   value="{{ $ecs_flight_transactionsItem->ticket_fare }}"
                                                                   id=""
                                                                   aria-describedby="helpId" placeholder="">
                                                        </div>
                                                        <div class="input-group">
                                                            <label for="">Penalties</label>
                                                            <input type="text" class="form-control" name="penalties"
                                                                   value="{{ $ecs_flight_transactionsItem->penalties }}"
                                                                   id=""
                                                                   aria-describedby="helpId" placeholder="">
                                                        </div>
                                                        <div class="input-group">
                                                            <label for="">Remarks</label>
                                                            <textarea class="form-control" name="remarks"
                                                                      rows="3">{{ $ecs_flight_transactionsItem->remarks }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">Save Changes
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @if(!$logged_in_user->isEcsClient)
        <div class="modal fade" id="modalCreate-ecs_flights" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('frontend.ecs_flights_ajax.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Flight</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="ecs_booking_id" value="{{ $ecs_booking->id }}">
                            <input type="hidden" name="client_id" value="{{ $ecs_booking->client_id }}">
                            <input type="hidden" name="booking_reference" value="{{ $ecs_booking->booking_reference }}">
                            <div class="form-group"><label>Flight</label>
                                <input type="text" class="form-control" name="flight"></div>
                            <div class="form-group"><label>Class</label>
                                <input type="text" class="form-control" name="class"></div>
                            <div class="form-group"><label>Flight Date</label>
                                <input type="date" class="form-control" name="flight_date"></div>
                            <div class="form-group"><label>Depart From</label>
                                <input type="text" class="form-control" name="depart_from"></div>
                            <div class="form-group"><label>Departure Time</label>
                                <input type="datetime-local" class="form-control" name="departure_time"></div>
                            <div class="form-group"><label>Arrive At</label>
                                <input type="datetime-local" class="form-control" name="arrive_at"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalCreate-ecs_flight_transactions" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('frontend.ecs_flight_transactions.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Flight Transaction</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="ecs_booking_id" value="{{ $ecs_booking->id }}">
                            <input type="hidden" name="client_id" value="{{ $ecs_booking->client_id }}">
                            <input type="hidden" name="booking_reference" value="{{ $ecs_booking->booking_reference }}">
                            <div class="form-group"><label>Name</label>
                                <input type="text" class="form-control" name="name"></div>
                            <div class="form-group"><label>Ticket Number</label>
                                <input type="text" class="form-control" name="ticket_number"></div>
{{--                            <div class="form-group"><label>Trx Id</label>--}}
{{--                                <input type="text" class="form-control" name="trx_id"></div>--}}
                            <div class="form-group"><label>Is Cancelled</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_cancelled" value="no">
                                    <label class="form-check-label">no</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_cancelled" value="yes">
                                    <label class="form-check-label">yes</label>
                                </div>
                            </div>
                            <div class="form-group"><label>Service Fee</label>
                                <input type="number" step="0.01" class="form-control" name="service_fee"
                                       value="{{ $ecs_booking->client_idRelation->service_charge_amount }}" required>
                            </div>
                            {{--                        <div class="form-group"><label>Client Approved At</label>--}}
                            {{--                            <input type="datetime-local" class="form-control" name="client_approved_at"></div>--}}
                            {{--                        <div class="form-group"><label>Client Approver</label>--}}
                            {{--                            <select class="form-control" name="client_approver_id">--}}
                            {{--                                <option value="">-- Select --</option>--}}
                            {{--                                @foreach(\App\Models\EcsClientUser::where('client_id', $ecs_booking->client_id)->get() as $opt)--}}
                            {{--                                    <option value="{{ $opt->id }}">{{ $opt->user->full_name }}</option>--}}
                            {{--                                @endforeach--}}
                            {{--                            </select>--}}
                            {{--                        </div>--}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
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
    <script>
        $(document).ready(function () {
            var table = new DataTable('.table', {
                "paging": false,
                scrollY: 465,
                layout: {
                    // top: {
                    //     searchBuilder: { }
                    // },
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                }
            });
        });
    </script>
@endpush

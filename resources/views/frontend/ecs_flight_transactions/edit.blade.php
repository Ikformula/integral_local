@extends('frontend.layouts.app')

@section('title', 'Edit Request ('.$ecs_flight_transaction->source.')')
@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <form action="{{ route('frontend.ecs_flight_transactions.update', $ecs_flight_transaction->id) }}"
              method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-header"><strong>Passenger & Ticket</strong></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="client_id">Client:</label>
                                <select class="form-control select2" id="client_id" name="client_id" required>
                                    <option value="" disabled selected>Select a client</option>
                                    @foreach(\App\Models\EcsClient::all() as $client_item)
                                        <option value="{{ $client_item->id }}" {{ $client_item->id == $ecs_flight_transaction->client_id ? 'selected' : '' }}>{{ $client_item->name_and_balance }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name', $ecs_flight_transaction->name) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Ticket Number</label>
                                <input type="text" name="ticket_number" class="form-control"
                                       value="{{ old('ticket_number', $ecs_flight_transaction->ticket_number) }}"
                                       required>
                            </div>
                            <div class="form-group">
                                <label>Booking Reference</label>
                                <input type="text" name="booking_reference" class="form-control"
                                       value="{{ old('booking_reference', $ecs_flight_transaction->booking_reference) }}">
                            </div>
                            <div class="form-group">
                                <label>Penalties</label>
                                <input type="number" step="0.01" name="penalties" class="form-control"
                                       value="{{ old('penalties', $ecs_flight_transaction->penalties) }}">
                            </div>
                            <div class="form-group">
                                <label>Ticket Fare</label>
                                <input type="number" step="0.01" name="ticket_fare" class="form-control"
                                       value="{{ old('ticket_fare', $ecs_flight_transaction->ticket_fare) }}">
                            </div>
                            <div class="form-group">
                                <label>Service Fee</label>
                                <input type="number" step="0.01" name="service_fee" class="form-control"
                                       value="{{ old('service_fee', $ecs_flight_transaction->service_fee) }}">
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="for_date" class="form-control"
                                       value="{{ old('for_date', optional($ecs_flight_transaction->for_date)->toDateString()) }}">
                            </div>
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea name="remarks"
                                          class="form-control">{{ old('remarks', $ecs_flight_transaction->remarks) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header"><strong>Flight Segments</strong></div>
                        <div class="card-body">
                            @php
                                $flights = $ecs_flight_transaction->flights;
                                $maxFlights = 4;
                            @endphp
                            @for($i = 0; $i < $maxFlights; $i++)
                                @php $flight=$flights[$i] ?? null; @endphp
                                <div class="border rounded p-2 mb-2">
                                    <input type="hidden" name="flights[{{ $i }}][id]" value="{{ $flight->id ?? '' }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-2">
                                            <label>Flight</label>
                                            <input type="text" name="flights[{{ $i }}][flight]" class="form-control"
                                                   value="{{ old('flights.'.$i.'.flight', $flight->flight ?? '') }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Class</label>
                                            <input type="text" name="flights[{{ $i }}][class]" class="form-control"
                                                   value="{{ old('flights.'.$i.'.class', $flight->class ?? '') }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Flight Date</label>
                                            <input type="date" name="flights[{{ $i }}][flight_date]"
                                                   class="form-control"
                                                   value="{{ old('flights.'.$i.'.flight_date', $flight->flight_date ?? '') }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Depart From</label>
                                            <input type="text" name="flights[{{ $i }}][depart_from]"
                                                   class="form-control"
                                                   value="{{ old('flights.'.$i.'.depart_from', $flight->depart_from ?? '') }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Departure Time</label>
                                            <input type="text" name="flights[{{ $i }}][departure_time]"
                                                   class="form-control"
                                                   value="{{ old('flights.'.$i.'.departure_time', $flight->departure_time ?? '') }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Arrive At</label>
                                            <input type="text" name="flights[{{ $i }}][arrive_at]" class="form-control"
                                                   value="{{ old('flights.'.$i.'.arrive_at', $flight->arrive_at ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    @if($ecs_flight_transaction->taxes && $ecs_flight_transaction->taxes->count())
                    <div class="card mb-3">
                        <div class="card-header"><strong>Taxes</strong></div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($ecs_flight_transaction->taxes as $i => $tax)
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="hidden" name="taxes[{{ $i }}][id]" value="{{ $tax->id }}">
                                            <div class="form-group col-md-6">
                                                <label>{{ $tax->tax_name }}</label>
                                                <input type="number" step="0.01" name="taxes[{{ $i }}][amount]"
                                                       class="form-control"
                                                       value="{{ old('taxes.'.$i.'.amount', $tax->amount) }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="ticket_fare">Total for this Request</label>
                                <input type="text" name="total_for_request"
                                       id="total_for_request"
                                       class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Transaction</button>
                    <a href="{{ route('frontend.ecs_flight_transactions.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </form>

        <div class="row mt-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><strong>Actions</strong></div>
                    <div class="card-body">
                        @if($ecs_flight_transaction->is_cancelled == 'no')
                            <form
                                action="{{ route('frontend.ecs_flight_transactions.cancel', $ecs_flight_transaction->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to cancel this flight transaction? This action cannot be undone.')">
                                @csrf
                                <div class="form-group">
                                    <label for="cancel_comment">Cancellation Comment (optional)</label>
                                    <textarea name="cancel_comment" id="cancel_comment"
                                              class="form-control">{{ old('cancel_comment', $ecs_flight_transaction->cancel_comment) }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btn-block">Cancel Flight Transaction
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning">
                                This flight transaction was cancelled.
                                @if($ecs_flight_transaction->cancel_comment)
                                    <p><strong>Reason:</strong> {{ $ecs_flight_transaction->cancel_comment }}</p>
                                @endif
                            </div>
                        @endif

                    <!-- Push To Reconciliation / Reverse From Reconciliation -->
{{--                        @if(!$ecs_flight_transaction->pushed_to_reconciliation_at)--}}
{{--                            <form method="POST"--}}
{{--                                  action="{{ route('frontend.ecs_flight_transactions.push.to.reconciliation', $ecs_flight_transaction->id) }}"--}}
{{--                                  style="display:inline;">--}}
{{--                                @csrf--}}
{{--                                <button type="submit" class="btn btn-dark btn-block mt-2"--}}
{{--                                        onclick="return confirm('Push this transaction to reconciliation?')">Push To--}}
{{--                                    Reconciliation--}}
{{--                                </button>--}}
{{--                            </form>--}}
{{--                        @else--}}
{{--                            <form method="POST"--}}
{{--                                  action="{{ route('frontend.ecs_flight_transactions.reverse.from.reconciliation', $ecs_flight_transaction->id) }}"--}}
{{--                                  style="display:inline;">--}}
{{--                                @csrf--}}
{{--                                <button type="submit" class="btn btn-secondary btn-block mt-2"--}}
{{--                                        onclick="return confirm('Reverse this transaction from reconciliation?')">--}}
{{--                                    Reverse From Reconciliation--}}
{{--                                </button>--}}
{{--                            </form>--}}
{{--                        @endif--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        function calculateTotal() {
            let penalties = parseFloat(document.querySelector('[name="penalties"]').value) || 0;
            let ticketFare = parseFloat(document.querySelector('[name="ticket_fare"]').value) || 0;
            let serviceFee = parseFloat(document.querySelector('[name="service_fee"]').value) || 0;
            let taxTotal = 0;
            document.querySelectorAll('input[name^="taxes"][name$="[amount]"]').forEach(function (input) {
                let val = parseFloat(input.value) || 0;
                taxTotal += val;
            });
            let total = penalties + ticketFare + serviceFee + taxTotal;
            document.getElementById('total_for_request').value = total.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            let fields = ['[name="penalties"]', '[name="ticket_fare"]', '[name="service_fee"]', 'input[name^="taxes"][name$="[amount]"]'];
            fields.forEach(function (selector) {
                document.querySelectorAll(selector).forEach(function (el) {
                    el.addEventListener('input', calculateTotal);
                });
            });
            calculateTotal();
        });
    </script>
@endpush

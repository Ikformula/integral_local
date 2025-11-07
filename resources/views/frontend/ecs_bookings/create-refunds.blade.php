<!-- resources/views/frontend/ecs_bookings/create.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Add New Request')
@push('after-styles')
<link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Request</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.ecs_flight_transactions.store.group') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="client_id">Client</label>
                            <select name="client_id" class="form-control" required>
                                <option value="">-- Select -- one</option>
                                @foreach(\App\Models\EcsClient::all() as $client)
                                    <option value="{{ $client->id }}">{{ $client->name_and_balance }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-8">

                                <div class="row">
                                    <div class="col-sm-8">
                                        <strong>SURNAME / FIRSTNAME AS IT APPEARS ON CRANE</strong>
                                    </div>
                                    <div class="col-sm-4">
                                        <strong>TICKET NUMBER</strong>
                                    </div>
                                </div>
                                @for($i = 1; $i <= 9; $i++)
                                    <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <input type="text" name="pax_names[{{ $i }}]" class="form-control form-control-sm pax_names" value="{{ old('pax_names.' . $i) }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input type="text" name="ticket_numbers[{{ $i }}]"
                                                class="form-control form-control-sm ticket_numbers" value="{{ old('ticket_numbers.' . $i) }}">
                                        </div>
                                    </div>
                            </div>
                            @endfor

                            <div class="row">
                                <div class="col">
                                    @for($i = 1; $i <= 4; $i++)
                                        <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Flight</label>
                                                <input type="text" name="flight[{{ $i }}]" class="form-control form-control-sm" value="{{ old('flight.' . $i) }}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Class</label>
                                                <input type="text" name="class[{{ $i }}]" class="form-control form-control-sm" value="{{ old('class.' . $i) }}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Flight Date</label>
                                                <input type="date" name="flight_date[{{ $i }}]" class="form-control form-control-sm" value="{{ old('flight_date.' . $i) }}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>
                                                    Depart From
                                                </label>
                                                <select name="depart_from[{{ $i }}]" class="form-control form-control-sm">
                                                    @php $old_depart_from = old('depart_from.' . $i); @endphp
                                                    @foreach(config('ecs.locations', []) as $loc)
                                                    <option value="{{ $loc }}" {{ $old_depart_from == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Departure Time</label>
                                                <input type="text" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" placeholder="HH:mm" name="departure_time[{{ $i }}]" class="form-control form-control-sm" value="{{ old('departure_time.' . $i) }}">
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label>
                                                    To
                                                </label>
                                                <select name="arrive_at[{{ $i }}]" class="form-control form-control-sm">
                                                    @php $old_arrive_at = old('arrive_at.' . $i); @endphp
                                                    @foreach(config('ecs.locations', []) as $loc)
                                                    <option value="{{ $loc }}" {{ $old_arrive_at == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                </div>
                                @endfor
                            </div>
                        </div>


                </div>

                <div class="col-md-4">
                    <div class="card border-primary">
                        <div class="card-body">

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="booking_reference">Booking Reference</label>
                                        <input type="text" name="booking_reference" id="booking_reference"
                                            class="form-control"
                                            value="{{ old('booking_reference') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="ticket_fare">Ticket Fare</label>
                                        <input type="number" step="0.01" name="ticket_fare" id="ticket_fare"
                                            class="form-control" value="{{ old('ticket_fare') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="penalties">Penalties</label>
                                        <input type="number" step="0.01" value="{{ old('penalties', 0) }}" name="penalties" id="penalties"
                                            class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Service Fee</label>
                                        <input type="number" step="0.01" class="form-control" name="service_fee"
                                            value="{{ old('service_fee', $client->service_charge_amount ?? 0) }}"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="for_date">Date</label>
                                        <input type="date" name="for_date" id="for_date" class="form-control" value="{{ old('for_date') }}" required>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks" rows="5">{{ old('remarks') }}</textarea>
                                        {{-- <small class="form-text text-muted">Kindly explain in detail</small>--}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="ticket_fare">Total per Ticket</label>
                                        <input type="text" name="total_per_ticket"
                                            id="total_per_ticket"
                                            class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="ticket_fare">Total for this Request</label>
                                        <input type="text" name="total_for_request"
                                            id="total_for_request"
                                            class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">Submit</button>
                            <a href="{{ route('frontend.ecs_bookings.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                    {{-- <div class="form-group">--}}
                    {{-- <label for="agent_user_id">Agent</label>--}}
                    {{-- <select name="agent_user_id" id="agent_user_id" class="form-control" required>--}}
                    {{-- --}}{{-- <option value="">-- Select --</option>--}}
                    {{-- --}}{{-- @foreach(\App\Models\Auth\User::all() as $option)--}}
                    {{-- <option value="{{ $logged_in_user->id }}"--}}
                    {{-- selected>{{ $logged_in_user->full_name }}</option>--}}
                    {{-- --}}{{-- @endforeach--}}
                    {{-- </select>--}}
                    {{-- </div>--}}
                    <input type="hidden" name="agent_user_id" value="{{ $logged_in_user->id }}">


                </div>
            </div>



            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@push('after-scripts')
<!-- Load CKEditor only if needed -->
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    document.querySelectorAll('textarea').forEach(function(textarea) {
        if (textarea.id) {
            ClassicEditor.create(textarea).catch(error => {
                console.error(error);
            });
        }
    });
</script>

<script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    function calculateTotals() {
        let ticketCount = 0;
        let ticketFare = parseFloat(document.getElementById('ticket_fare').value) || 0;
        let serviceFee = parseFloat(document.querySelector('[name="service_fee"]').value) || 0;
        let penalties = parseFloat(document.getElementById('penalties').value) || 0;

        // Sum all tax fields
        let taxTotal = 0;
        document.querySelectorAll('input[name^="tax["]').forEach(function(input) {
            let val = parseFloat(input.value) || 0;
            taxTotal += val;
        });

        // Count tickets with both pax_names and ticket_numbers filled
        for (let i = 1; i <= 9; i++) {
            let pax = document.querySelector('[name="pax_names[' + i + ']"]');
            let tkt = document.querySelector('[name="ticket_numbers[' + i + ']"]');
            if (pax && tkt && pax.value.trim() && tkt.value.trim()) {
                ticketCount++;
            }
        }

        // Per ticket total
        let perTicket = ticketFare + taxTotal + serviceFee + penalties;
        document.getElementById('total_per_ticket').value = perTicket.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Total for request
        let totalRequest = perTicket * ticketCount;
        document.getElementById('total_for_request').value = totalRequest.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Attach events
    document.addEventListener('DOMContentLoaded', function() {
        // All relevant fields
        let fields = [
            '#ticket_fare', '[name="service_fee"]', '#penalties',
            'input[name^="tax["]',
            '.pax_names',
            '.ticket_numbers'
        ];
        fields.forEach(function(selector) {
            document.querySelectorAll(selector).forEach(function(el) {
                el.addEventListener('input', calculateTotals);
            });
        });
        calculateTotals(); // Initial calculation
    });
</script>
@endpush



<form action="{{ route('frontend.ecs_flight_transactions.store.group') }}" method="POST" onsubmit="return confirm('Are you sure you want to enter this refund?')">
    @csrf
<input type="hidden" name="source" value="REFUND">
    <div class="form-group">
        <label for="client_id">Client</label>
        <select name="client_id" id="client_id" class="form-control select2" required>
            <option value="">-- Select --</option>
            @foreach(\App\Models\EcsClient::all() as $option)
            <option value="{{ $option->id }}">{{ $option->name_and_balance }}</option>
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

            @php
                $locations = locations3letters();
            @endphp
        <div class="row">
            <div class="col">
                @for($i = 1; $i <= 4; $i++)
                    <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Flight {{ $i }}</label>
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
                                @foreach($locations as $loc)
                                <option value="{{ $loc }}" {{ $old_depart_from == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Depart. Time</label>
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
                                @foreach($locations as $loc)
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
                <div class="form-group">
                    <label for="booking_reference">Booking Reference</label>
                    <input type="text" name="booking_reference" id="booking_reference"
                        class="form-control"
                        value="{{ old('booking_reference') }}"
                        required>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="ticket_fare">Amount Per Ticket</label>
                            <input type="text" name="ticket_fare" id="ticket_fare"
                                class="form-control"
                                value="{{ old('ticket_fare') }}"
                                required>
                            <small class="form-text text-muted">If you enter a value here, it will be multiplied by the number of valid tickets to get the total refundable amount.</small>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="amount_refundable">Amount Refundable</label>
                            <input type="number" step="0.01" name="amount_refundable" min="100" id="amount_refundable" class="form-control" required>
                            <small class="form-text text-muted">If you change this value, it will be divided by the number of valid tickets to get the amount per ticket.</small>
                        </div>
                    </div>
                </div>

                @push('after-scripts')
                <script>
                    $(function() {
                        function countValidTickets() {
                            let count = 0;
                            for (let i = 1; i <= 9; i++) {
                                let name = $(`input[name='pax_names[${i}]']`).val();
                                let ticket = $(`input[name='ticket_numbers[${i}]']`).val();
                                if (name && ticket) count++;
                            }
                            return count;
                        }

                        let updating = false;

                        $('#ticket_fare').on('input', function() {
                            if (updating) return;
                            updating = true;
                            let fare = parseFloat($(this).val());
                            let n = countValidTickets();
                            if (!isNaN(fare) && n > 0) {
                                $('#amount_refundable').val((fare * n).toFixed(2));
                            }
                            updating = false;
                        });

                        $('#amount_refundable').on('input', function() {
                            if (updating) return;
                            updating = true;
                            let total = parseFloat($(this).val());
                            let n = countValidTickets();
                            if (!isNaN(total) && n > 0) {
                                $('#ticket_fare').val((total / n).toFixed(2));
                            }
                            updating = false;
                        });

                        // Also update on ticket fields change
                        $('.pax_names, .ticket_numbers').on('input', function() {
                            let fare = parseFloat($('#ticket_fare').val());
                            let total = parseFloat($('#amount_refundable').val());
                            let n = countValidTickets();
                            if (!isNaN(fare) && n > 0) {
                                $('#amount_refundable').val((fare * n).toFixed(2));
                            } else if (!isNaN(total) && n > 0) {
                                $('#ticket_fare').val((total / n).toFixed(2));
                            }
                        });
                    });
                </script>
                @endpush

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="for_date">Travel Date</label>
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

                <input type="hidden" name="agent_user_id" value="{{ $logged_in_user->id }}">
                <input type="hidden" name="source" value="REFUND">
                <button type="submit" class="btn btn-primary float-right">Submit</button>
                <a href="{{ route('frontend.ecs_bookings.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>



    </div>
    </div>

</form>

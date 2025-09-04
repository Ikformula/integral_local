
@push('after-styles')
    <style>
        .bg-lighter-blue {
            background-color: #cbddf8;
        }
    </style>
@endpush

<form action="{{ route('frontend.ecs_refunds.storeGroupRefunds') }}" method="POST" onsubmit="return confirm('Are you sure you want to enter this refund?')">
    @csrf

    <div class="form-group">
        <label for="client_id">Client</label>
        <select name="client_id" id="client_id" class="form-control select2" required>
            <option value="">-- Select --</option>
            @foreach(\App\Models\EcsClient::all() as $option)
                <option value="{{ $option->id }}">{{ $option->name_and_balance }}</option>
            @endforeach
        </select>
    </div>

    @for($i = 1; $i <= 9; $i++)
        <div class="row bg-{{ $i % 2 == 0 ? 'light' : 'lighter-blue' }}">
            <div class="col">
                <div class="form-group">
                    <label>({{ $i }}). Name</label>
                    <input type="text" name="gr_name[{{ $i }}]" class="form-control">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label>Ticket Number</label>
                    <input type="text" name="gr_ticket_number[{{ $i }}]" class="form-control">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label>Cost Code</label>
                    <input type="text" name="gr_cost_code[{{ $i }}]" class="form-control">
                </div>
            </div>
        </div>
    @endfor

    <div class="form-group">
        <label for="booking_reference">Booking Reference</label>
        <input type="text" name="booking_reference" id="booking_reference" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="route">Route</label>
        <input type="text" name="route" id="route" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="travel_date">Travel Date</label>
        <input type="date" name="travel_date" id="travel_date" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="for_date">For Date</label>
        <input type="text" name="for_date" id="for_date" class="form-control" value="{{ now()->toDateString() }}" required>
    </div>

    <div class="form-group">
        <label for="ticket_class">Class</label>
        <input type="text" name="ticket_class" id="ticket_class" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="amount_refundable">Amount Refundable</label>
        <input type="number" step="0.01" name="amount_refundable" min="100" id="amount_refundable" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="remarks">Remarks</label>
        <textarea class="form-control" name="remarks" id="remarks" rows="5"></textarea>
    </div>

    <input type="hidden" name="agent_user_id" value="{{ $logged_in_user->id }}">

    <button type="submit" class="btn btn-primary">Submit</button>
</form>




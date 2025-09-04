
@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush


<form action="{{ route('frontend.ecs_refunds.store') }}" method="POST" onsubmit="return confirm('Are you sure you want to enter this refund?')">
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

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="ticket_number">Ticket Number</label>
        <input type="text" name="ticket_number" id="ticket_number" class="form-control" required>
    </div>

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
        <label for="ticket_class">Class</label>
        <input type="text" name="ticket_class" id="ticket_class" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="amount_refundable">Amount Refundable</label>
        <input type="number" step="0.01" min="100" name="amount_refundable" id="amount_refundable" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="remarks">Remarks</label>
        <textarea class="form-control" name="remarks" id="remarks" rows="5"></textarea>
        <small class="form-text text-muted">Kindly explain in detail</small>
    </div>

    <input type="hidden" name="agent_user_id" value="{{ $logged_in_user->id }}">

    <button type="submit" class="btn btn-primary">Submit</button>
</form>


@push('after-scripts')
    <!-- Load CKEditor only if needed -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        document.querySelectorAll('textarea').forEach(function(textarea) {
            if(textarea.id) {
                ClassicEditor.create(textarea).catch(error => { console.error(error); });
            }
        });
    </script>

    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush

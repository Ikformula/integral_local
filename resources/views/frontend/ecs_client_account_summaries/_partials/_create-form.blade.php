<form action="{{ route('frontend.ecs_client_account_summaries.store') }}" method="POST" onsubmit="return confirm('Are you sure you want to proceed with this action? It\'ll affect the client\'s balance?')">
    @csrf

    <div class="form-group">
        <label for="client_id">Client</label>
        <select name="client_id" id="client_id" class="form-control select2" required>
            <option value="">-- Select --</option>
            @if(isset($client))
                <option value="{{ $client->id }}" selected>{{ $client->name_and_balance }}</option>
            @endif
            @foreach(\App\Models\EcsClient::all() as $option)
                <option value="{{ $option->id }}">{{ $option->name_and_balance }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="credit_amount">Amount</label>
        <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="trx_type">Type</label>
        <select class="form-control" name="trx_type" id="trx_type" required>
            <option disabled selected>--Select one</option>
            <option>Credit</option>
            <option>Debit</option>
        </select>
    </div>

    <div class="form-group">
        <label for="for_date">For Date</label>
        <input type="text" name="for_date" id="for_date" class="form-control" value="{{ now()->toDateString() }}" required>
    </div>

    <div class="form-group">
        <label for="details">Details</label>
        <input type="text" name="details" id="details" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
    @if(!isset($client))
    <a href="{{ route('frontend.ecs_client_account_summaries.index') }}" class="btn btn-secondary">Cancel</a>
    @endif
</form>

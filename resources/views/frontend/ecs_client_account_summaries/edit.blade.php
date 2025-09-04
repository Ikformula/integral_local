<!-- resources/views/frontend/ecs_client_account_summaries/edit.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Edit Client Account Summary')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Client Account Summary</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.ecs_client_account_summaries.update', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to update this?')">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="client_id">Client</label>
                            <select name="client_id" id="client_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach(\App\Models\EcsClient::all() as $option)
                                    <option value="{{ $option->id }}" {{ $item->client_id == $option->id ? 'selected' : '' }}>{{ $option->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="credit_amount">Credit Amount</label>
                            <input type="number" step="0.01" name="credit_amount" id="credit_amount" class="form-control" value="{{ $item->credit_amount }}" required>
                        </div>

                        <div class="form-group">
                            <label for="ticket_number">Ticket Number</label>
                            <input type="text" name="ticket_number" id="ticket_number" class="form-control" value="{{ $item->ticket_number }}" required>
                        </div>

                        <div class="form-group">
                            <label for="details">Details</label>
                            <input type="text" name="details" id="details" class="form-control" value="{{ $item->details }}" required>
                        </div>

                        <div class="form-group">
                            <label for="debit_amount">Debit Amount</label>
                            <input type="number" step="0.01" name="debit_amount" id="debit_amount" class="form-control" value="{{ $item->debit_amount }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('frontend.ecs_client_account_summaries.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        document.querySelectorAll('textarea').forEach(function(textarea) {
            ClassicEditor.create(textarea).catch(error => { console.error(error); });
        });
    </script>
@endpush

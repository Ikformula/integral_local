<!-- resources/views/frontend/ecs_refunds/edit.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Edit EcsRefund')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Refund</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.ecs_refunds.update', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to update this?')">
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
                            <label for="surname">Surname</label>
                            <input type="text" name="surname" id="surname" class="form-control" value="{{ $item->surname }}" required>
                        </div>

                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $item->first_name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="ticket_number">Ticket Number</label>
                            <input type="text" name="ticket_number" id="ticket_number" class="form-control" value="{{ $item->ticket_number }}" required>
                        </div>

                        <div class="form-group">
                            <label for="booking_reference">Booking Reference</label>
                            <input type="text" name="booking_reference" id="booking_reference" class="form-control" value="{{ $item->booking_reference }}" required>
                        </div>

                        <div class="form-group">
                            <label for="route">Route</label>
                            <input type="text" name="route" id="route" class="form-control" value="{{ $item->route }}" required>
                        </div>

                        <div class="form-group">
                            <label for="travel_date">Travel Date</label>
                            <input type="date" name="travel_date" id="travel_date" class="form-control" value="{{ $item->travel_date }}" required>
                        </div>

                        <div class="form-group">
                            <label for="ticket_class">Class</label>
                            <input type="text" name="ticket_class" id="ticket_class" class="form-control" value="{{ $item->ticket_class }}" required>
                        </div>

                        <div class="form-group">
                            <label for="amount_refundable">Amount Refundable</label>
                            <input type="number" step="0.01" name="amount_refundable" id="amount_refundable" class="form-control" value="{{ $item->amount_refundable }}" required>
                        </div>

                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control" name="remarks" id="remarks" rows="5">{{ $item->remarks }}</textarea>
                            <small class="form-text text-muted">Kindly explain in detail</small>
                        </div>


                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('frontend.ecs_refunds.index') }}" class="btn btn-secondary">Cancel</a>
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

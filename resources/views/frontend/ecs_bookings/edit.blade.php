<!-- resources/views/frontend/ecs_bookings/edit.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Edit EcsBooking')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Booking</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.ecs_bookings.update', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to update this?')">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="client_id">Client</label>
                            <select name="client_id" id="client_id" class="form-control" required>
                                <option value="">-- Select Client --</option>
                                @foreach(App\Models\EcsClient::all() as $client)
                                    <option value="{{ $client->id }}" {{ $client->id == $item->client_id }}>{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="booking_reference">Booking Reference</label>
                            <input type="text" name="booking_reference" id="booking_reference" class="form-control" value="{{ $item->booking_reference }}" required>
                        </div>

                        <div class="form-group">
                            <label for="penalties">Penalties</label>
                            <input type="number" step="0.01" name="penalties" id="penalties" class="form-control" value="{{ $item->penalties }}" required readonly>
                        </div>

                        <div class="form-group">
                            <label for="ticket_fare">Ticket Fare</label>
                            <input type="number" step="0.01" name="ticket_fare" id="ticket_fare" class="form-control" value="{{ $item->ticket_fare }}" required readonly>
                        </div>

                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control" name="remarks" id="remarks" rows="5">{{ $item->remarks }}</textarea>
                            <small class="form-text text-muted">Kindly explain in detail</small>
                        </div>

                        <div class="form-group">
                            <label for="for_date">Date</label>
                            <input type="date" name="for_date" id="for_date" class="form-control" value="{{ $item->for_date }}" required>
                        </div>

{{--                        <div class="form-group">--}}
{{--                            <label for="agent_user_id">Agent</label>--}}
{{--                            <select name="agent_user_id" id="agent_user_id" class="form-control" required>--}}
{{--                                @foreach(\App\Models\Auth\User::all() as $option)--}}
{{--                                    <option value="{{ $item->agent_user_id }}" selected>{{ $item->full_name }}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('frontend.ecs_bookings.index') }}" class="btn btn-secondary">Cancel</a>
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

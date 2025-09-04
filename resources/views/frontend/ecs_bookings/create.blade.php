<!-- resources/views/frontend/ecs_bookings/create.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Add New Booking')
@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Add New Booking</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('frontend.ecs_bookings.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="client_id">Client</label>
                                <h4>{{ $client->name_and_balance }}</h4>
                                <input type="hidden" name="client_id" value="{{ $client->id }}">
                            </div>

                            <div class="form-group">
                                <label for="booking_reference">Booking Reference</label>
                                <input type="text" name="booking_reference" id="booking_reference" class="form-control"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="penalties">Penalties</label>
                                <input type="number" step="0.01" name="penalties" id="penalties" class="form-control"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="ticket_fare">Ticket Fare</label>
                                <input type="number" step="0.01" name="ticket_fare" id="ticket_fare"
                                       class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks" rows="5"></textarea>
                                <small class="form-text text-muted">Kindly explain in detail</small>
                            </div>

                            <div class="form-group">
                                <label for="for_date">Date</label>
                                <input type="date" name="for_date" id="for_date" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="agent_user_id">Agent</label>
                                <select name="agent_user_id" id="agent_user_id" class="form-control" required>
                                    {{--                                <option value="">-- Select --</option>--}}
                                    {{--                                @foreach(\App\Models\Auth\User::all() as $option)--}}
                                    <option value="{{ $logged_in_user->id }}"
                                            selected>{{ $logged_in_user->full_name }}</option>
                                    {{--                                @endforeach--}}
                                </select>
                            </div>

                            @if($client->taxes() && count($client->taxes()))
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Taxes</h3>
                                            </div>
                                            <div class="card-body">
                                                @csrf
                                                <div class="row">
                                                    @foreach($client->taxes() as $tax)
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>{{ strtoupper($tax) }}</label>
                                                                <input type="number" min="0" value="0"
                                                                       name="tax[{{ $tax }}]" class="form-control"
                                                                       required>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif


                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('frontend.ecs_bookings.index') }}" class="btn btn-secondary">Cancel</a>
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
        document.querySelectorAll('textarea').forEach(function (textarea) {
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
    </script>@endpush

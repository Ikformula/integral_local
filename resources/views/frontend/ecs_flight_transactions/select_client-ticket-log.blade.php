@extends('frontend.layouts.app')

@section('title', 'ECS Ticket Log - Select Client')
@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="container-fluid">

    <div class="card">
{{--        <div class="card-header">--}}
{{--            <h3 class="card-title">@yield('title')</h3>--}}
{{--        </div>--}}
        <div class="card-body">
            <form method="GET" action="{{ route('frontend.ecs_flight_transactions.ticketLog', '') }}">
                <div class="row">
                    <div class="col-8">
                        <div class="mb-0">
                            <select class="form-control" id="client_id" name="client_id" required>
                                <option value="" disabled selected>Select a client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name_and_balance }}</option>
                                @endforeach
                            </select>
                            <label for="client_id">Select Client:</label>
                        </div>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-block">View Ticket Log</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('select').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush

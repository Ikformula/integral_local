@extends('frontend.layouts.app')

@section('title', 'ACFA Dash')

@section('content')

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Get Fare Analysis</h4>
                    <form action="{{ route('frontend.airline_fares.processAcfaAirlines') }}" method="GET">
                        {{--                            @csrf--}}
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>From</label>
                                    <select class="form-control" name="depart_from_port" required>
                                        @foreach($locations as $key => $location)
                                            <option value="{{ $key }}">{{ $location }} ({{ $key }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>To</label>
                                    <select class="form-control" name="arrive_at_port" required>
                                        @foreach($locations as $key => $location)
                                            <option value="{{ $key }}">{{ $location }} ({{ $key }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" name="departureDate" min="{{ now()->toDateString() }}" required
                                           class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- checkbox -->
                                <div class="form-group">
                                    @foreach($airlines as $airline)
                                        <div class="form-check">
                                            <input class="form-check-input" name="airline_ids[]"
                                                   id="airline_{{ $airline->id }}" value="{{ $airline->id }}"
                                                   type="checkbox">
                                            <label class="form-check-label">{{ $airline->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn bg-navy btn-block">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>

@endsection

@extends('frontend.layouts.app')

@section('title', 'Make Booking')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Book Flight Ticket</div>

                        <div class="card-body">
                            <form action="" method="POST">
                                @csrf
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="trip_type" id="round_trip"
                                           value="round_trip">
                                    <label class="form-check-label" for="round_trip">
                                        Round Trip
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="trip_type" id="one_way"
                                           value="one_way" checked>
                                    <label class="form-check-label" for="one_way">
                                        One Way
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="flying_from">Flying From</label>
                                            <select name="flying_from" id="flying_from" class="form-control">
                                                <option>Abuja (ABV)</option>
                                                <option>Benin (BNI)</option>
                                                <option>Jos (JOS)</option>
                                                <option>Kano (KAN)</option>
                                                <option>Lagos (LOS)</option>
                                                <option>Maiduguri (MIU)</option>
                                                <option>Port Harcourt (PHC)</option>
                                                <option>Port Harcourt NAF Base (PHG)</option>
                                                <option>Sokoto (SKO)</option>
                                                <option>Warri (QRW)</option>
                                                <option>Yola (YOL)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="flying_To">Flying To</label>
                                            <select name="flying_To" id="flying_To" class="form-control">
                                                <option>Abuja (ABV)</option>
                                                <option>Benin (BNI)</option>
                                                <option>Jos (JOS)</option>
                                                <option>Kano (KAN)</option>
                                                <option>Lagos (LOS)</option>
                                                <option>Maiduguri (MIU)</option>
                                                <option>Port Harcourt (PHC)</option>
                                                <option>Port Harcourt NAF Base (PHG)</option>
                                                <option>Sokoto (SKO)</option>
                                                <option>Warri (QRW)</option>
                                                <option>Yola (YOL)</option>
                                            </select>
                                        </div>
{{--                                        <div class="form-group">--}}
{{--                                            <label for=""></label>--}}
{{--                                            <input type="text" name="" id="" class="form-control">--}}
{{--                                        </div>--}}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Departure Date</label>
                                            <input type="date" name="departure_date" id="departure_date" value="{{ $tomorrow->toDateString() }}" readonly class="form-control">
                                            <small class="text-muted">You can only make 24 hour bookings</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="adult">Adult</label>
                                            <select name="adult" id="adult"
                                                    class="form-control">
                                                <option selected disabled>Adult</option>
                                                @for($i = 1; $i <= 9; $i++)
                                                    <option>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="child">Child</label>
                                            <select name="child" id="child"
                                                    class="form-control">
                                                <option selected disabled>Child</option>
                                                @for($i = 1; $i <= 9; $i++)
                                                    <option>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="infant">Infant</label>
                                            <select name="infant" id="infant"
                                                    class="form-control">
                                                <option selected disabled>Infant</option>
                                                @for($i = 1; $i <= 9; $i++)
                                                    <option>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="button" class="btn btn-lg bg-maroon">
                                        FLIGHT SEARCH
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/. container-fluid -->
    </section>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-sm">
        Launch Small Modal
    </button>

@endsection


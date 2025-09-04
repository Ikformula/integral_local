@extends('frontend.layouts.app')

@section('title', 'Make Booking')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Proceed to Make Booking</div>

                        <div class="card-body">
                            <form action="{{ route('frontend.staff_travel.bookingInit') }}" method="POST">
                                @csrf
                                <div class="alert alert-warning" role="alert">
                                    <strong>Redirect Alert!</strong>
                                    You are going to be redirected to a third party website to make the booking.<br>
                                    Do you want to proceed?
                                    Your booking balance is <strong>{{ $booking_balance }}</strong>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-lg bg-maroon">
                                        PROCEED
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/. container-fluid -->
    </section>

@endsection


@extends('frontend.layouts.app')

@section('title', 'Kindly detail your complain' )

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card arik-card">
                        <div class="card-header">
@yield('title')
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="passengerName">Passenger Name:</label>
                                    <input type="text" class="form-control" id="passengerName" name="passengerName">
                                </div>
                                <div class="form-group">
                                    <label for="nameOnCard">Name on Card:</label>
                                    <input type="text" class="form-control" id="nameOnCard" name="nameOnCard">
                                </div>
                                <div class="form-group">
                                    <label for="lastFourDigits">Last Four Digits:</label>
                                    <input type="text" class="form-control" id="lastFourDigits" name="lastFourDigits" maxlength="4">
                                </div>
                                <div class="form-group">
                                    <label for="bank">Bank:</label>
                                    <input type="text" class="form-control" id="bank" name="bank">
                                </div>
                                <div class="form-group">
                                    <label for="dateOfTransaction">Date of Transaction:</label>
                                    <input type="date" class="form-control" id="dateOfTransaction" name="dateOfTransaction">
                                </div>
                                <div class="form-group">
                                    <label for="amount">Amount:</label>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="route">Route:</label>
                                    <input type="text" class="form-control" id="route" name="route">
                                </div>
                                <div class="form-group">
                                    <label for="ticketRefNo">Ticket/Ref No:</label>
                                    <input type="text" class="form-control" id="ticketRefNo" name="ticketRefNo">
                                </div>
                                <div class="form-group">
                                    <label for="travelDate">Travel Date:</label>
                                    <input type="date" class="form-control" id="travelDate" name="travelDate">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="requestRemark">Request/Remark:</label>
                                    <textarea class="form-control" id="requestRemark" name="requestRemark" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="paymentMethod">Payment Method:</label>
                                    <input type="text" class="form-control" id="paymentMethod" name="paymentMethod">
                                </div>
                                <div class="form-group">
                                    <label for="paymentReference">Payment Reference:</label>
                                    <input type="text" class="form-control" id="paymentReference" name="paymentReference">
                                </div>
                                <div class="form-group">
                                    <label for="amountConfirmed">Amount Confirmed:</label>
                                    <input type="number" class="form-control" id="amountConfirmed" name="amountConfirmed" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="cardType">Card Type:</label>
                                    <input type="text" class="form-control" id="cardType" name="cardType">
                                </div>
                                <div class="form-group">
                                    <label for="userId">User ID:</label>
                                    <input type="number" class="form-control" id="userId" name="userId">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

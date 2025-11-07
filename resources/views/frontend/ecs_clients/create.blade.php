@extends('frontend.layouts.app')

@section('title', 'Add New Client')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Client</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.ecs_clients.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="current_balance">Current Balance</label>
                            <input type="number" name="current_balance" id="current_balance" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="service_charge_amount">Service Charge Amount</label>
                            <input type="number" min="0" value="0" name="service_charge_amount" id="service_charge_amount" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="deal_code">Deal Code</label>
                            <input type="text" name="deal_code" id="deal_code" class="form-control">
                        </div>

                        <h5>Account Type</h5>
                        <div class="form-group">
                        <div class="form-check">
                            <input type="radio" name="account_type" id="PREPAID" class="form-check-input" value="PREPAID">
                            <label class="form-check-label" for="PREPAID">PREPAID</label>
                        </div>

                        <div class="form-check">
                            <input type="radio" name="account_type" id="POSTPAID" class="form-check-input" value="POSTPAID">
                            <label class="form-check-label" for="POSTPAID">POSTPAID</label>
                        </div>
                        </div>


                        <h5>Applicable Taxes</h5>
                        <div class="row">
                                    @foreach($taxes as $tax)
                                <div class="col-sm-6">
                                    <!-- checkbox -->
                                    <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tax_columns[]" value="{{ $tax }}">
                                        <label class="form-check-label">{{ $tax }}</label>
                                    </div>
                                    </div>
                                </div>
                                    @endforeach
                        </div>

                        <h5>Applicable Additional Fees</h5>
                        <div class="row">
                            @foreach($additional_fees as $fee)
                                <div class="col-sm-6">
                                    <!-- checkbox -->
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fee_columns[]" value="{{ $fee }}">
                                            <label class="form-check-label">{{ unSlug($fee) }}</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row mt-3">
                                <div class="col-sm-6">
                                    <!-- checkbox -->
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="select_category" value="1">
                                            <label class="form-check-label">Require Category</label>
                                        </div>
                                    </div>
                                </div>
                        </div>


                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('frontend.ecs_clients.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

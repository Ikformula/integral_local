{{-- filepath: c:\laragon\www\arik_web_portals\resources\views\frontend\ecs_clients\edit.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Edit Client')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Client</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.ecs_clients.update', $ecs_client->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to update this?')">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $ecs_client->name }}" required>
                        </div>

                        @if(1 < 0)
                        <div class="form-group">
                            <label for="current_balance">Current Balance</label>
                            <input type="number" name="current_balance" id="current_balance" class="form-control" value="{{ $ecs_client->current_balance }}" required>
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="service_charge_amount">Service Charge Amount</label>
                            <input type="number" min="0" name="service_charge_amount" id="service_charge_amount" class="form-control" value="{{ $ecs_client->service_charge_amount }}" required>
                        </div>
                        <div class="form-group">
                            <label for="deal_code">Deal Code</label>
                            <input type="text" name="deal_code" id="deal_code" class="form-control" value="{{ $ecs_client->deal_code }}">
                        </div>

                        <h5>Account Type</h5>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="radio" name="account_type" id="PREPAID" class="form-check-input" value="PREPAID" @if(isset($ecs_client->account_type) && $ecs_client->account_type == 'PREPAID') checked @endif>
                                <label class="form-check-label" for="PREPAID">PREPAID</label>
                            </div>

                            <div class="form-check">
                                <input type="radio" name="account_type" id="POSTPAID" class="form-check-input" value="POSTPAID" @if(isset($ecs_client->account_type) && $ecs_client->account_type == 'POSTPAID') checked @endif>
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
                                            <input class="form-check-input" type="checkbox" name="tax_columns[]" value="{{ $tax }}" {{ $ecs_client->taxes() && in_array($tax, $ecs_client->taxes()) ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $tax }}</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('frontend.ecs_clients.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('frontend.layouts.app')

@section('title', 'Edit ICU Activity Report')

@push('after-styles')
<link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Edit ICU Activity Report</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.icu_activities.update', $icu_activitiesItem->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group"><label>Category</label>
                            <select class="form-control" name="category">
                                <option value="">-- Select --</option>
                                <option value="{{ $icu_activitiesItem->category }}" selected>{{ $icu_activitiesItem->category }}</option>
                                <option>PAYMENT REQUEST</option>
                                <option>CASH ADVANCE</option>
                                <option>RETIREMENT</option>
                                <option>SALARY ADVANCE</option>
                                <option>MEMO REVIEW</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Description</label>
                            <textarea class="form-control" name="description">{{ $icu_activitiesItem->description }}</textarea>
                        </div>
                        <div class="form-group"><label>Department</label>
                            <select class="form-control" name="department">
                                <option value="">-- Select --</option>
                                <option value="{{ $icu_activitiesItem->department }}" selected>{{ $icu_activitiesItem->department }}</option>
                                @include('includes.partials._departments-option-list')
                            </select>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group"><label>Initial Naira Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="initial_naira_amount" id="initial_naira_amount" value="{{ $icu_activitiesItem->initial_naira_amount }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group"><label>Final Naira Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="naira_amount" id="naira_amount" value="{{ $icu_activitiesItem->naira_amount }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group"><label>Cost Savings (Naira)</label>
                                    <input type="number" step="0.01" class="form-control" name="cost_savings_naira" id="cost_savings_naira" value="{{ $icu_activitiesItem->cost_savings_naira }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group"><label>Initial US Dollar Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="initial_us_dollar_amount" id="initial_us_dollar_amount" value="{{ $icu_activitiesItem->initial_us_dollar_amount }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group"><label>Final US Dollar Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="us_dollar_amount" id="us_dollar_amount" value="{{ $icu_activitiesItem->us_dollar_amount }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group"><label>Cost Savings (USD)</label>
                                    <input type="number" step="0.01" class="form-control" name="cost_savings_usd" id="cost_savings_usd" value="{{ $icu_activitiesItem->cost_savings_usd }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group"><label>Initial Euro Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="initial_euro_amount" id="initial_euro_amount" value="{{ $icu_activitiesItem->initial_euro_amount }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group"><label>Final Euro Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="euro_amount" id="euro_amount" value="{{ $icu_activitiesItem->euro_amount }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group"><label>Cost Savings (Euro)</label>
                                    <input type="number" step="0.01" class="form-control" name="cost_savings_euro" id="cost_savings_euro" value="{{ $icu_activitiesItem->cost_savings_euro }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group"><label>Initial GBP Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="initial_gbp_amount" id="initial_gbp_amount" value="{{ $icu_activitiesItem->initial_gbp_amount }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group"><label>Final GBP Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="gbp_amount" id="gbp_amount" value="{{ $icu_activitiesItem->gbp_amount }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group"><label>Cost Savings (GBP)</label>
                                    <input type="number" step="0.01" class="form-control" name="cost_savings_gbp" id="cost_savings_gbp" value="{{ $icu_activitiesItem->cost_savings_gbp }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group"><label>Date Treated</label>
                            <input type="date" class="form-control" name="date_treated" value="{{ $icu_activitiesItem->date_treated }}">
                        </div>
                        <div class="form-group"><label>Vendor</label>
                            <select class="form-control" name="vendor_id">
                                <option value="">-- Select --</option>
                                @foreach($external_vendors as $opt)
                                <option value="{{ $opt->id }}" {{ $opt->id==$icu_activitiesItem->vendor_id?'selected':'' }}>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Enter Vendor name below if not in the list</label>
                            <input type="text" name="vendor_name" class="form-control" value="{{ $icu_activitiesItem->vendor_name }}">
                        </div>
                        <div class="form-group"><label>Beneficiary Staff</label>
                            <select class="form-control" name="beneficiary_staff_ara_id">
                                <option value="">-- Select --</option>
                                @foreach($staff_members as $opt)
                                <option value="{{ $opt->staff_ara_id }}" {{ $opt->id==$icu_activitiesItem->beneficiary_staff_ara_id?'selected':'' }}>{{ $opt->name_and_ara }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group"><label>Beneficiary Details</label>
                            <textarea class="form-control" name="beneficiary_details" rows="4">{{ $icu_activitiesItem->beneficiary_details }}</textarea>
                        </div>
                        <input type="hidden" name="entered_by_user_id" value="{{ $logged_in_user->id }}">
                        <div class="form-group">
                            <strong>Observation Status</strong>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="Ongoing" {{ $icu_activitiesItem->status == 'Ongoing' ? 'checked' : '' }}>
                                <label class="form-check-label">Ongoing</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="Completed" {{ $icu_activitiesItem->status == 'Completed' ? 'checked' : '' }}>
                                <label class="form-check-label">Completed</label>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('frontend.icu_activities.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
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

<script>
    $(document).ready(function() {
        function updateCurrencyFields(initialId, finalId, savingsId) {
            var initial = parseFloat($('#' + initialId).val()) || 0;
            var final = parseFloat($('#' + finalId).val()) || 0;
            var savings = initial - final;
            $('#' + savingsId).val(savings.toFixed(2));
        }

        $('#initial_naira_amount, #naira_amount').on('input', function() {
            updateCurrencyFields('initial_naira_amount', 'naira_amount', 'cost_savings_naira');
        });
        $('#initial_us_dollar_amount, #us_dollar_amount').on('input', function() {
            updateCurrencyFields('initial_us_dollar_amount', 'us_dollar_amount', 'cost_savings_usd');
        });
        $('#initial_euro_amount, #euro_amount').on('input', function() {
            updateCurrencyFields('initial_euro_amount', 'euro_amount', 'cost_savings_euro');
        });
        $('#initial_gbp_amount, #gbp_amount').on('input', function() {
            updateCurrencyFields('initial_gbp_amount', 'gbp_amount', 'cost_savings_gbp');
        });

        // Initial trigger to set savings if form is prefilled
        updateCurrencyFields('initial_naira_amount', 'naira_amount', 'cost_savings_naira');
        updateCurrencyFields('initial_us_dollar_amount', 'us_dollar_amount', 'cost_savings_usd');
        updateCurrencyFields('initial_euro_amount', 'euro_amount', 'cost_savings_euro');
        updateCurrencyFields('initial_gbp_amount', 'gbp_amount', 'cost_savings_gbp');
    });
</script>

@endpush

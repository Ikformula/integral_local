@extends('frontend.layouts.app')

@push('after-styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
<link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('title', 'Manage Internal Control Activity Reports')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <p class="mr-2 font-weight-bold">{{ $quarterLabel }}</p>
            <form method="get" class="form-inline-flex-wrap">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <input type="date" name="date_treated_from" value="{{ $filters['date_treated_from'] }}"
                                class="form-control">
                            <label>Date Treated From</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <input type="date" name="date_treated_to" value="{{ $filters['date_treated_to'] }}"
                                class="form-control">
                            <label>To Date Treated</label>
                        </div>
                    </div>
                    {{-- <div class="col">--}}
                    {{-- <div class="form-group">--}}
                    {{-- <input type="date" name="created_from" value="{{ $filters['created_from'] }}" class="form-control">--}}
                    {{-- <label>Created From</label>--}}
                    {{-- </div>--}}
                    {{-- </div>--}}
                    {{-- <div class="col">--}}
                    {{-- <div class="form-group">--}}
                    {{-- <input type="date" name="created_to" value="{{ $filters['created_to'] }}" class="form-control">--}}
                    {{-- <label>Created To</label>--}}
                    {{-- </div>--}}
                    {{-- </div>--}}
                    <div class="col">
                        <div class="form-group">
                            <select name="category" class="form-control">
                                <option value="">Category</option>
                                @foreach($categories as $cat)
                                <option
                                    value="{{ $cat }}" {{ $filters['category'] == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            <label>Category</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <select name="department" class="form-control">
                                <option value="">Department</option>
                                @foreach($departments as $dept)
                                <option
                                    value="{{ $dept }}" {{ $filters['department'] == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                            <label>Department</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <select name="trx_currency" class="form-control">
                                <option value="">Currency</option>
                                @foreach($currencies as $cur)
                                <option
                                    value="{{ $cur }}" {{ $filters['trx_currency'] == $cur ? 'selected' : '' }}>{{ $cur }}</option>
                                @endforeach
                            </select>
                            <label>Currency</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <select name="vendor_id" class="form-control">
                                <option value="">Vendor</option>
                                @foreach($external_vendors as $vendor)
                                <option
                                    value="{{ $vendor->id }}" {{ $filters['vendor_id'] == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                            <label>Vendor</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <select name="beneficiary_staff_ara_id" class="form-control">
                                <option value="">Beneficiary Staff</option>
                                @foreach($staff_members as $staff)
                                <option
                                    value="{{ $staff->staff_ara_id }}" {{ $filters['beneficiary_staff_ara_id'] == $staff->staff_ara_id ? 'selected' : '' }}>{{ $staff->name_and_ara }}</option>
                                @endforeach
                            </select>
                            <label>Beneficiary Staff</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <input type="number" name="min_amount" value="{{ $filters['min_amount'] }}"
                                class="form-control" placeholder="Min Amount">
                            <label>Min Amount</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <input type="number" name="max_amount" value="{{ $filters['max_amount'] }}"
                                class="form-control" placeholder="Max Amount">
                            <label>Max Amount</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('frontend.icu_activities.index') }}" class="btn bg-maroon">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-2">
            @component('frontend.components.dashboard_stat_widget-small-box', ['title' => 'Total Activities', 'icon' => 'list', 'colour' => 'primary'])
            {{ $total_count }}
            @endcomponent
        </div>
        <div class="col-md-2">
            @component('frontend.components.dashboard_stat_widget-small-box', ['title' => 'Total Naira Trx', 'icon' => 'money-bill', 'colour' => 'warning'])
            {{ number_format($total_naira) }}
            @endcomponent
        </div>
        <div class="col-md-2">
            @component('frontend.components.dashboard_stat_widget-small-box', ['title' => 'Total USD Trx', 'icon' => 'dollar-sign', 'colour' => 'secondary'])
            {{ number_format($total_usd) }}
            @endcomponent
        </div>
        <div class="col-md-2">
            @component('frontend.components.dashboard_stat_widget-small-box', ['title' => 'Total EUR Trx', 'icon' => 'euro-sign', 'colour' => 'primary'])
            {{ number_format($total_euro) }}
            @endcomponent
        </div>
        <div class="col-md-2">
            @component('frontend.components.dashboard_stat_widget-small-box', ['title' => 'Total GBP Trx', 'icon' => 'sterling-sign', 'colour' => 'warning'])
            {{ number_format($total_gbp) }}
            @endcomponent
        </div>
        <div class="col-md-2">
            @component('frontend.components.dashboard_stat_widget-small-box', ['title' => 'Unique Vendors', 'icon' => 'users', 'colour' => 'secondary'])
            {{ $unique_vendors }}
            @endcomponent
        </div>
        <div class="col-md-2">
            @component('frontend.components.dashboard_stat_widget-small-box', ['title' => 'Unique Departments', 'icon' => 'building', 'colour' => 'primary'])
            {{ $unique_departments }}
            @endcomponent
        </div>
    </div>

    <!-- Top 10 Vendors Bar Chart -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h5 class="card-title">Top 10 Highest Vendors (by Total Amount)</h5>
                </div>
                <div class="card-body">
                    <canvas id="topVendorsChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill"
                                href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home"
                                aria-selected="true"
                                style="border-top-left-radius: 1.25rem; border-top-right-radius: 1.25rem;">Internal
                                Control Activity Report List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link bg-maroon" id="custom-tabs-three-profile-tab" data-toggle="pill"
                                href="#custom-tabs-three-profile" role="tab"
                                aria-controls="custom-tabs-three-profile" aria-selected="false"
                                style="border-top-left-radius: 1.25rem; border-top-right-radius: 1.25rem;"><i class="fa fa-plus"></i> Add New Report</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-three-home" role="tabpanel"
                            aria-labelledby="custom-tabs-three-home-tab">
                            <table id="icu_activities-tbl" class="table table-bordered text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Department</th>
                                        <th>Naira Amount</th>
                                        <th>Us Dollar Amount</th>
                                        <th>Euro Amount</th>
                                        <th>GBP Amount</th>
                                        <th>Trx Currency</th>
                                        <th>Date Treated</th>
                                        <th>Vendor</th>
                                        <th>Beneficiary Staff</th>
                                        <th>Beneficiary Details</th>
                                        {{-- <th>Status</th>--}}
                                        {{-- <th>Status Changed At</th>--}}
                                        <th>Entered By User</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($icu_activities as $key => $icu_activitiesItem)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $icu_activitiesItem->category }}</td>
                                        <td>{!! $icu_activitiesItem->description !!}</td>
                                        <td>{{ $icu_activitiesItem->department }}</td>
                                        <td>{{ checkIntNumber($icu_activitiesItem->naira_amount) }}</td>
                                        <td>{{ checkIntNumber($icu_activitiesItem->us_dollar_amount) }}</td>
                                        <td>{{ checkIntNumber($icu_activitiesItem->euro_amount) }}</td>
                                        <td>{{ checkIntNumber($icu_activitiesItem->gbp_amount) }}</td>
                                        <td>{{ $icu_activitiesItem->trx_currency }}</td>
                                        <td>{{ $icu_activitiesItem->date_treated }}</td>
                                        <td>{{ $icu_activitiesItem->vendor_idRelation ? $icu_activitiesItem->vendor_idRelation->name : '' }}</td>
                                        <td>{{ $icu_activitiesItem->beneficiary_staff_ara_idRelation ? $icu_activitiesItem->beneficiary_staff_ara_idRelation->name_and_ara : '' }}</td>
                                        <td>{{ $icu_activitiesItem->beneficiary_details }}</td>
                                        {{-- <td>{{ $icu_activitiesItem->status }}</td>--}}
                                        {{-- <td>{{ $icu_activitiesItem->status_changed_at }}</td>--}}
                                        <td>{{ $icu_activitiesItem->entered_by_user_idRelation ? $icu_activitiesItem->entered_by_user_idRelation->full_name : '' }}</td>
                                        <td>
                                            <a href="{{ route('frontend.icu_activities.edit', $icu_activitiesItem->id) }}" class="btn btn-sm btn-info">Edit</a>
                                            <form
                                                action="{{ route('frontend.icu_activities.destroy', $icu_activitiesItem->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel"
                            aria-labelledby="custom-tabs-three-profile-tab">
                            <form action="{{ route('frontend.icu_activities.store') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Add New Activity Report</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group"><label>Category</label>
                                        <select class="form-control" name="category">
                                            <option value="">-- Select --</option>
                                            <option>PAYMENT REQUEST</option>
                                            <option>CASH ADVANCE</option>
                                            <option>RETIREMENT</option>
                                            <option>SALARY ADVANCE</option>
                                            <option>MEMO REVIEW</option>
                                            <option>OBSERVATION</option>
                                        </select>
                                    </div>
                                    <div class="form-group"><label>Description</label>
                                        <textarea class="form-control" name="description" rows="4"></textarea>
                                    </div>
                                    <div class="form-group"><label>Department</label>
                                        <select class="form-control" name="department">
                                            <option value="">-- Select --</option>
                                            @include('includes.partials._departments-option-list')
                                        </select>
                                    </div>


                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group"><label>Initial Naira Amount</label>
                                                <input type="number" step="0.01" class="form-control" name="initial_naira_amount" id="initial_naira_amount">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group"><label>Final Naira Amount</label>
                                                <input type="number" step="0.01" class="form-control" name="naira_amount" id="naira_amount">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group"><label>Cost Savings (Naira)</label>
                                                <input type="number" step="0.01" class="form-control" name="cost_savings_naira" id="cost_savings_naira" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group"><label>Initial US Dollar Amount</label>
                                                <input type="number" step="0.01" class="form-control" name="initial_us_dollar_amount" id="initial_us_dollar_amount">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group"><label>Final US Dollar Amount</label>
                                                <input type="number" step="0.01" class="form-control" name="us_dollar_amount" id="us_dollar_amount">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group"><label>Cost Savings (USD)</label>
                                                <input type="number" step="0.01" class="form-control" name="cost_savings_usd" id="cost_savings_usd" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group"><label>Initial Euro Amount</label>
                                                <input type="number" step="0.01" class="form-control" name="initial_euro_amount" id="initial_euro_amount">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group"><label>Final Euro Amount</label>
                                                <input type="number" step="0.01" class="form-control" name="euro_amount" id="euro_amount">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group"><label>Cost Savings (Euro)</label>
                                                <input type="number" step="0.01" class="form-control" name="cost_savings_euro" id="cost_savings_euro" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group"><label>Initial GBP Amount</label>
                                                <input type="number" step="0.01" class="form-control" name="initial_gbp_amount" id="initial_gbp_amount">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group"><label>Final GBP Amount</label>
                                                <input type="number" step="0.01" class="form-control" name="gbp_amount" id="gbp_amount">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group"><label>Cost Savings (GBP)</label>
                                                <input type="number" step="0.01" class="form-control" name="cost_savings_gbp" id="cost_savings_gbp" readonly>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- <div class="form-group"><label>Trx Currency</label><br>--}}
                                    {{-- @foreach($currencies as $currency)--}}
                                    {{-- <div class="form-check form-check-inline">--}}
                                    {{-- <input class="form-check-input" type="radio"--}}
                                    {{-- name="trx_currency"--}}
                                    {{-- value="{{ $currency }}">--}}
                                    {{-- <label class="form-check-label">{{ $currency }}</label>--}}
                                    {{-- </div>--}}
                                    {{-- @endforeach--}}
                                    {{-- </div>--}}


                                    <div class="form-group"><label>Date Treated</label>
                                        <input type="date" class="form-control" name="date_treated"
                                            value="{{ now()->toDateString() }}" required>
                                    </div>
                                    <div class="form-group"><label>Vendor</label>
                                        <select class="form-control" name="vendor_id">
                                            <option value="">-- Select --</option>
                                            @foreach($external_vendors as $opt)
                                            <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Enter Vendor name below if not in the list</label>
                                        <input type="text" name="vendor_name" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Beneficiary Staff</label>
                                        <select class="form-control" name="beneficiary_staff_ara_id">
                                            <option value="">-- Select --</option>
                                            @foreach($staff_members as $opt)
                                            <option
                                                value="{{ $opt->staff_ara_id }}">{{ $opt->name_and_ara }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group"><label>Beneficiary Details</label>
                                        <textarea class="form-control" name="beneficiary_details"
                                            rows="4"></textarea>
                                    </div>
                                    {{-- <div class="form-group"><label>Status</label>--}}
                                    {{-- <input type="text" class="form-control" name="status"></div>--}}
                                    {{-- <div class="form-group"><label>Status Changed At</label>--}}
                                    {{-- <input type="datetime-local" class="form-control" name="status_changed_at"></div>--}}
                                    <input type="hidden" name="entered_by_user_id"
                                        value="{{ $logged_in_user->id }}">
                                </div>

                                <div class="form-group">
                                    <strong>Observation Status</strong>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="Ongoing">
                                        <label class="form-check-label">Ongoing</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status"
                                            value="Completed">
                                        <label class="form-check-label">Completed</label>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>


</div>
@endsection


@push('after-scripts')
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script>
    $(document).ready(function() {
        var table = new DataTable('.table', {
            'paging': false,
            scrollY: 465,
        });


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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare vendor data from backend

    var topVendors = @json($topVendors);
    var vendorLabels = topVendors.map(function(v) {
        return v.name;
    });
    var vendorTotals = topVendors.map(function(v) {
        return v.total;
    });

    var ctx = document.getElementById('topVendorsChart').getContext('2d');
    var topVendorsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: vendorLabels,
            datasets: [{
                label: 'Total Amount',
                data: vendorTotals,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y', // horizontal bar
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Amount'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Vendor'
                    }
                }
            }
        }
    });
</script>

<script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $('select').select2({
        theme: 'bootstrap4'
    });
</script>
@endpush

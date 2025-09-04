@extends('frontend.layouts.app')

@push('after-styles')
<link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('title', 'ECS Weekly Report')

@section('content')
<div class="container-fluid">
    <form method="get" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="date" name="from_date" value="{{ $from_date }}" class="form-control">
                <label>From Date</label>
            </div>
            <div class="col-md-3">
                <input type="date" name="to_date" value="{{ $to_date }}" class="form-control">
                <label>To Date</label>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true" style="border-top-left-radius: 1.25rem; border-top-right-radius: 1.25rem;">Client Transactions & Refunds</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false" style="border-top-left-radius: 1.25rem; border-top-right-radius: 1.25rem;">Staff Ticket & Refund Summary</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                            <table class="table table-bordered table-striped w-100" id="client-report-table">
                                <thead>
                                <tr>
                                    <th>CLIENT</th>
                                    <th>ACCOUNT TYPE</th>
                                    <th>NO OF TKTS</th>
                                    <th>VALUE OF TRANSACTIONS</th>
                                    <th>NO OF TKT REFUNDED</th>
                                    <th>REFUND AMOUNT</th>
                                    <th>ACCOUNT BALANCE</th>
                                    <th>BALANCE AS OF {{ \Carbon\Carbon::parse($to_date)->isoFormat('Do of MMMM YYYY') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($client_report as $row)
                                    <tr>
                                        <td>{{ $row['client'] }}</td>
                                        <td>{{ $row['account_type'] }}</td>
                                        <td>{{ $row['no_of_tkts'] }}</td>
                                        <td>{{ number_format($row['value_of_trx'], 2) }}</td>
                                        <td>{{ $row['no_of_tkt_refunded'] }}</td>
                                        <td>{{ number_format($row['refund_amount'], 2) }}</td>
                                        <td>{{ number_format($row['account_balance'], 2) }}</td>
                                        <td>{{ number_format($row['balance_as_of_last_day'], 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-weight-bold bg-light">
                                    <td>{{ $client_report_total['client'] }}</td>
                                    <td>{{ $client_report_total['account_type'] }}</td>
                                    <td>{{ $client_report_total['no_of_tkts'] }}</td>
                                    <td>{{ number_format($client_report_total['value_of_trx'], 2) }}</td>
                                    <td>{{ $client_report_total['no_of_tkt_refunded'] }}</td>
                                    <td>{{ number_format($client_report_total['refund_amount'], 2) }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                            <table class="table table-bordered table-striped w-100" id="staff-report-table">
                                <thead>
                                <tr>
                                    <th>STAFF NAME</th>
                                    <th>VALUE OF TICKETS</th>
                                    <th>VALUE OF REFUND</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($staff_report as $row)
                                    <tr>
                                        <td>{{ $row['staff_name'] }}</td>
                                        <td>{{ number_format($row['value_of_tickets'], 2) }}</td>
                                        <td>{{ number_format($row['value_of_refund'], 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
<script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $('.select2').select2({
        theme: 'bootstrap4'
    });
</script>

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/dataTables.searchBuilder.js"></script>
<script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/searchBuilder.dataTables.js"></script>
<script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        var table = new DataTable('.table', {
            "paging": true,
            layout: {
                // top: {
                //     searchBuilder: { }
                // },
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                }
            }
        });
    });
</script>
@endpush
